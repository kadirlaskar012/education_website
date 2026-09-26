import { NextRequest, NextResponse } from 'next/server';
import { getDb } from '@/lib/mongodb';
import { scrapeSource, processAndSaveNotice } from '@/lib/scraper';

export const dynamic = 'force-dynamic';
export const maxDuration = 60; // Max execution time for Vercel

export async function GET(request: NextRequest) {
  // Optional security check for Vercel Cron
  const authHeader = request.headers.get('authorization');
  const cronSecret = process.env.CRON_SECRET;
  
  if (cronSecret && authHeader !== `Bearer ${cronSecret}`) {
    // If request has no auth and is not from local dev or vercel cron header
    const isVercelCron = request.headers.get('user-agent')?.includes('vercel-cron');
    if (!isVercelCron && process.env.NODE_ENV === 'production') {
      return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
    }
  }

  try {
    const db = await getDb();
    
    // Pick 3 active sources that haven't been scraped recently (Batching to stay well under Vercel timeout)
    const sources = await db
      .collection('sources')
      .find({ is_active: true })
      .sort({ last_scraped_at: 1 })
      .limit(4)
      .toArray();

    if (sources.length === 0) {
      return NextResponse.json({ message: 'No active scraper sources found' });
    }

    const results = [];
    let totalNewNotices = 0;

    for (const src of sources) {
      const items = await scrapeSource({
        id: src.id,
        name: src.name,
        feed_url: src.feed_url,
        official_portal_url: src.official_portal_url,
        state: src.state,
        category_id: src.category_id,
      });

      let addedCount = 0;
      for (const item of items) {
        const saved = await processAndSaveNotice(item);
        if (saved) {
          addedCount++;
          totalNewNotices++;
        }
      }

      // Update last scraped time
      await db.collection('sources').updateOne(
        { _id: src._id },
        { $set: { last_scraped_at: new Date() } }
      );

      results.push({
        source: src.name,
        itemsFound: items.length,
        newNoticesSaved: addedCount,
      });
    }

    return NextResponse.json({
      success: true,
      timestamp: new Date().toISOString(),
      totalNewNotices,
      batchDetails: results,
    });
  } catch (error) {
    console.error('Cron scrape error:', error);
    return NextResponse.json(
      { success: false, error: (error as Error).message },
      { status: 500 }
    );
  }
}
