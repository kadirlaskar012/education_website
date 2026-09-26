import { getDb } from './mongodb';
import { Article, Category, Source, SiteSetting } from '@/types';

// Helper to serialize MongoDB documents (converting Date and ObjectId to plain serializable values)
// eslint-disable-next-line @typescript-eslint/no-explicit-any
export function serializeDoc<T>(doc: any): T {
  if (!doc) return doc;
  const item = { ...doc };
  if (item._id) item._id = item._id.toString();
  if (item.published_at instanceof Date) item.published_at = item.published_at.toISOString();
  if (item.created_at instanceof Date) item.created_at = item.created_at.toISOString();
  if (item.updated_at instanceof Date) item.updated_at = item.updated_at.toISOString();
  if (item.last_scraped_at instanceof Date) item.last_scraped_at = item.last_scraped_at.toISOString();
  return item as T;
}

// 1. Navigation Categories
export async function getNavCategories(): Promise<Category[]> {
  try {
    const db = await getDb();
    const categories = await db
      .collection('categories')
      .find({ is_nav_visible: true })
      .sort({ sort_order: 1 })
      .toArray();
    return categories.map(c => serializeDoc<Category>(c));
  } catch (error) {
    console.error('getNavCategories error:', error);
    return [];
  }
}

// 2. All Categories
export async function getAllCategories(): Promise<Category[]> {
  try {
    const db = await getDb();
    const categories = await db
      .collection('categories')
      .find({})
      .sort({ sort_order: 1 })
      .toArray();
    return categories.map(c => serializeDoc<Category>(c));
  } catch (error) {
    console.error('getAllCategories error:', error);
    return [];
  }
}

// 3. Breaking Ticker Notices
export async function getBreakingNews(limit = 10): Promise<Article[]> {
  try {
    const db = await getDb();
    const articles = await db
      .collection('articles')
      .find({ status: 'published' })
      .sort({ published_at: -1 })
      .limit(limit)
      .toArray();
    return articles.map(a => serializeDoc<Article>(a));
  } catch (error) {
    console.error('getBreakingNews error:', error);
    return [];
  }
}

// 4. Top 10 Latest Notices for Hero Section
export async function getTop10Notices(): Promise<Article[]> {
  try {
    const db = await getDb();
    const articles = await db
      .collection('articles')
      .find({ status: 'published' })
      .sort({ published_at: -1 })
      .limit(10)
      .toArray();
    return articles.map(a => serializeDoc<Article>(a));
  } catch (error) {
    console.error('getTop10Notices error:', error);
    return [];
  }
}

// 5. Sidebar Latest 10 Notices
export async function getSidebarNotices(limit = 10): Promise<Article[]> {
  return getTop10Notices();
}

// 6. Articles by Category or Slug
export async function getArticlesByCategory(
  categorySlug: string,
  page = 1,
  limit = 12,
  stateSlug?: string
): Promise<{ articles: Article[]; total: number; totalPages: number; category: Category | null }> {
  try {
    const db = await getDb();
    const category = await db.collection('categories').findOne({ slug: categorySlug });
    
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    const filter: any = { status: 'published' };
    if (category) {
      filter.category_id = category.id;
    } else if (categorySlug !== 'all') {
      filter.category_slug = categorySlug;
    }

    if (stateSlug && stateSlug !== 'all-india' && stateSlug !== '') {
      filter.state = stateSlug;
    }

    const total = await db.collection('articles').countDocuments(filter);
    const skip = (page - 1) * limit;

    const articles = await db
      .collection('articles')
      .find(filter)
      .sort({ published_at: -1 })
      .skip(skip)
      .limit(limit)
      .toArray();

    return {
      articles: articles.map(a => serializeDoc<Article>(a)),
      total,
      totalPages: Math.ceil(total / limit),
      category: category ? serializeDoc<Category>(category) : null
    };
  } catch (error) {
    console.error('getArticlesByCategory error:', error);
    return { articles: [], total: 0, totalPages: 0, category: null };
  }
}

// 7. Articles by State
export async function getArticlesByState(
  stateSlug: string,
  page = 1,
  limit = 12
): Promise<{ articles: Article[]; total: number; totalPages: number }> {
  try {
    const db = await getDb();
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    const filter: any = { status: 'published' };
    if (stateSlug && stateSlug !== 'all-india') {
      filter.state = stateSlug;
    }

    const total = await db.collection('articles').countDocuments(filter);
    const skip = (page - 1) * limit;

    const articles = await db
      .collection('articles')
      .find(filter)
      .sort({ published_at: -1 })
      .skip(skip)
      .limit(limit)
      .toArray();

    return {
      articles: articles.map(a => serializeDoc<Article>(a)),
      total,
      totalPages: Math.ceil(total / limit)
    };
  } catch (error) {
    console.error('getArticlesByState error:', error);
    return { articles: [], total: 0, totalPages: 0 };
  }
}

// 8. Single Article Detail by Slug
export async function getArticleBySlug(slug: string): Promise<Article | null> {
  try {
    const db = await getDb();
    const article = await db.collection('articles').findOne({ slug });
    if (!article) return null;

    // Increment view count asynchronously
    db.collection('articles').updateOne({ _id: article._id }, { $inc: { view_count: 1 } }).catch(() => {});

    return serializeDoc<Article>(article);
  } catch (error) {
    console.error('getArticleBySlug error:', error);
    return null;
  }
}

// 9. Related Articles
export async function getRelatedArticles(categoryId: number, excludeSlug: string, limit = 4): Promise<Article[]> {
  try {
    const db = await getDb();
    const articles = await db
      .collection('articles')
      .find({
        status: 'published',
        category_id: categoryId,
        slug: { $ne: excludeSlug }
      })
      .sort({ published_at: -1 })
      .limit(limit)
      .toArray();
    return articles.map(a => serializeDoc<Article>(a));
  } catch (error) {
    console.error('getRelatedArticles error:', error);
    return [];
  }
}

// 10. Search Articles
export async function searchArticles(
  query: string,
  page = 1,
  limit = 12
): Promise<{ articles: Article[]; total: number; totalPages: number }> {
  try {
    const db = await getDb();
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    const filter: any = { status: 'published' };

    if (query && query.trim()) {
      const cleanQ = query.trim();
      filter.$or = [
        { title: { $regex: cleanQ, $options: 'i' } },
        { excerpt: { $regex: cleanQ, $options: 'i' } },
        { official_source_name: { $regex: cleanQ, $options: 'i' } },
        { category_name: { $regex: cleanQ, $options: 'i' } },
        { state_name: { $regex: cleanQ, $options: 'i' } }
      ];
    }

    const total = await db.collection('articles').countDocuments(filter);
    const skip = (page - 1) * limit;

    const articles = await db
      .collection('articles')
      .find(filter)
      .sort({ published_at: -1 })
      .skip(skip)
      .limit(limit)
      .toArray();

    return {
      articles: articles.map(a => serializeDoc<Article>(a)),
      total,
      totalPages: Math.ceil(total / limit)
    };
  } catch (error) {
    console.error('searchArticles error:', error);
    return { articles: [], total: 0, totalPages: 0 };
  }
}

// 11. Scraper Sources
export async function getSources(): Promise<Source[]> {
  try {
    const db = await getDb();
    const sources = await db.collection('sources').find({}).sort({ name: 1 }).toArray();
    return sources.map(s => serializeDoc<Source>(s));
  } catch (error) {
    console.error('getSources error:', error);
    return [];
  }
}

// 12. Site Settings
export async function getSiteSettings(): Promise<Record<string, string>> {
  try {
    const db = await getDb();
    const settings = await db.collection('settings').find({}).toArray();
    const map: Record<string, string> = {
      site_name: 'EduGov News',
      site_tagline: 'Instant & Verified Educational & Public Recruitment Portal'
    };
    for (const s of settings) {
      map[s.key] = s.value;
    }
    return map;
  } catch (error) {
    console.error('getSiteSettings error:', error);
    return {
      site_name: 'EduGov News',
      site_tagline: 'Instant & Verified Educational & Public Recruitment Portal'
    };
  }
}

// 13. Admin Metrics
export async function getAdminMetrics() {
  try {
    const db = await getDb();
    const totalArticles = await db.collection('articles').countDocuments({});
    const publishedArticles = await db.collection('articles').countDocuments({ status: 'published' });
    const draftArticles = await db.collection('articles').countDocuments({ status: 'draft' });
    const inReviewArticles = await db.collection('articles').countDocuments({ status: 'in_review' });
    const activeSources = await db.collection('sources').countDocuments({ is_active: true });

    return {
      totalArticles,
      publishedArticles,
      draftArticles,
      inReviewArticles,
      activeSources
    };
  } catch (error) {
    console.error('getAdminMetrics error:', error);
    return {
      totalArticles: 0,
      publishedArticles: 0,
      draftArticles: 0,
      inReviewArticles: 0,
      activeSources: 0
    };
  }
}
