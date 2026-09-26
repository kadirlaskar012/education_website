import axios from 'axios';
import * as cheerio from 'cheerio';
import crypto from 'crypto';
import { getDb } from './mongodb';

export interface RawNoticeItem {
  title: string;
  url: string;
  pdfUrl?: string;
  date?: string;
  sourceId: number;
  sourceName: string;
  state: string;
  categoryId: number;
}

// Compute hash of URL for deduplication
export function computeUrlHash(url: string): string {
  return crypto.createHash('sha256').update(url.trim().toLowerCase()).digest('hex');
}

// Generate URL slug from title
export function generateSlug(title: string): string {
  const clean = title
    .toLowerCase()
    .replace(/[^\w\s-]/g, '')
    .replace(/\s+/g, '-')
    .replace(/-+/g, '-')
    .slice(0, 80);
  const randomSuffix = Math.floor(1000 + Math.random() * 9000);
  return `${clean}-${randomSuffix}`;
}

// Scrape HTML table / link list source
export async function scrapeSource(source: {
  id: number;
  name: string;
  feed_url: string;
  official_portal_url: string;
  state: string;
  category_id: number;
}): Promise<RawNoticeItem[]> {
  const items: RawNoticeItem[] = [];

  try {
    const response = await axios.get(source.feed_url, {
      timeout: 8000,
      headers: {
        'User-Agent':
          'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        Accept: 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
      },
    });

    const $ = cheerio.load(response.data);

    // Scan table rows or link containers
    $('table tr, ul li, .notice-item, .view-content tr').each((_, el) => {
      const linkEl = $(el).find('a').first();
      if (!linkEl || linkEl.length === 0) return;

      const title = (linkEl.text() || $(el).text()).replace(/\s+/g, ' ').trim();
      let href = linkEl.attr('href') || '';

      if (!title || title.length < 5 || !href) return;

      // Handle relative URLs
      if (href.startsWith('/')) {
        const urlObj = new URL(source.feed_url);
        href = `${urlObj.origin}${href}`;
      } else if (!href.startsWith('http')) {
        href = `${source.feed_url.replace(/\/[^/]*$/, '/')}${href}`;
      }

      const isPdf = href.toLowerCase().endsWith('.pdf');

      items.push({
        title,
        url: href,
        pdfUrl: isPdf ? href : undefined,
        sourceId: source.id,
        sourceName: source.name,
        state: source.state || 'all-india',
        categoryId: source.category_id || 1,
      });
    });

    return items.slice(0, 10); // Take top 10 fresh notices
  } catch (error) {
    console.error(`Scrape error for source ${source.name} (${source.feed_url}):`, error);
    return [];
  }
}

// Process raw notice and store into MongoDB Atlas
export async function processAndSaveNotice(item: RawNoticeItem): Promise<boolean> {
  try {
    const db = await getDb();
    const urlHash = computeUrlHash(item.url);

    // Deduplication check
    const existing = await db.collection('articles').findOne({ original_url_hash: urlHash });
    if (existing) {
      return false; // Already ingested
    }

    const slug = generateSlug(item.title);
    const category = (await db.collection('categories').findOne({ id: item.categoryId })) || {
      id: 1,
      name: 'Recruitment',
      slug: 'recruitment',
    };

    // Generate structured HTML content & tables
    const contentHtml = `
      <div class="notice-quick-summary">
        <p>The <strong>${item.sourceName}</strong> has officially released a public notification regarding <strong>${item.title}</strong>. Candidates and applicants are advised to review the official details, eligibility criteria, and important dates structured below.</p>
      </div>

      <div class="data-table-wrapper" style="margin: 1.5rem 0;">
        <table class="data-table">
          <thead>
            <tr>
              <th>Resource / Document</th>
              <th>Official Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Official Notification Reference</td>
              <td><a href="${item.url}" target="_blank" rel="noopener noreferrer nofollow" class="link-btn">View Notice ↗</a></td>
            </tr>
            ${
              item.pdfUrl
                ? `<tr><td>Official PDF Document</td><td><a href="${item.pdfUrl}" target="_blank" rel="noopener noreferrer nofollow" class="link-btn">Download PDF ↗</a></td></tr>`
                : ''
            }
          </tbody>
        </table>
      </div>

      <div class="notice-guidelines">
        <h3>Step-by-Step Instructions</h3>
        <ol style="margin-left: 1.25rem; line-height: 1.8;">
          <li>Visit the official portal of <strong>${item.sourceName}</strong>.</li>
          <li>Navigate to the 'Recruitment / Examination / Notice Board' section.</li>
          <li>Locate and download the official circular for <em>"${item.title}"</em>.</li>
          <li>Carefully read all instructions, eligibility requirements, and deadlines.</li>
        </ol>
      </div>
    `;

    const doc = {
      category_id: category.id,
      category_name: category.name,
      category_slug: category.slug,
      source_id: item.sourceId,
      official_source_name: item.sourceName,
      state: item.state,
      state_name: item.state.replace(/-/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase()),
      title: item.title,
      slug,
      excerpt: `Official notification published by ${item.sourceName} regarding ${item.title}. Click to access verified download links and details.`,
      content_html: contentHtml,
      original_url: item.url,
      original_url_hash: urlHash,
      pdf_url: item.pdfUrl || '',
      status: 'published',
      content_score: 100,
      view_count: 0,
      is_breaking: false,
      is_trending: false,
      is_top_featured: false,
      meta_title: `${item.title} - Official Notification - EduGov News`,
      meta_description: `Download verified official notification PDF and exam updates for ${item.title} released by ${item.sourceName}.`,
      published_at: new Date(),
      created_at: new Date(),
      updated_at: new Date(),
    };

    await db.collection('articles').insertOne(doc);
    return true;
  } catch (error) {
    console.error('processAndSaveNotice error:', error);
    return false;
  }
}
