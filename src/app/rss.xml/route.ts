import { NextResponse } from 'next/server';
import { getDb } from '@/lib/mongodb';

export const revalidate = 300; // 5 minutes

export async function GET() {
  const baseUrl = process.env.NEXT_PUBLIC_SITE_URL || 'http://localhost:3000';

  try {
    const db = await getDb();
    const articles = await db
      .collection('articles')
      .find({ status: 'published' })
      .sort({ published_at: -1 })
      .limit(50)
      .toArray();

    const itemsXml = articles
      .map((art) => {
        const title = (art.title || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        const desc = (art.excerpt || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        const link = `${baseUrl}/news/${art.slug}`;
        const pubDate = new Date(art.published_at).toUTCString();

        return `
    <item>
      <title>${title}</title>
      <link>${link}</link>
      <guid isPermaLink="true">${link}</guid>
      <description>${desc}</description>
      <category>${art.category_name || 'General'}</category>
      <pubDate>${pubDate}</pubDate>
    </item>`;
      })
      .join('\n');

    const rssXml = `<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>EduGov News — Official Education &amp; Recruitment Feed</title>
    <link>${baseUrl}</link>
    <description>Verified government job alerts, admit cards, exam schedules, and merit list results.</description>
    <language>en-in</language>
    <lastBuildDate>${new Date().toUTCString()}</lastBuildDate>
    <atom:link href="${baseUrl}/rss.xml" rel="self" type="application/rss+xml"/>
${itemsXml}
  </channel>
</rss>`;

    return new NextResponse(rssXml, {
      headers: {
        'Content-Type': 'application/xml; charset=utf-8',
        'Cache-Control': 's-maxage=300, stale-while-revalidate=600',
      },
    });
  } catch (error) {
    console.error('RSS Feed error:', error);
    return new NextResponse('Internal Server Error', { status: 500 });
  }
}
