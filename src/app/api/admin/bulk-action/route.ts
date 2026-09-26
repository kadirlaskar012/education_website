import { NextRequest, NextResponse } from 'next/server';
import { getDb } from '@/lib/mongodb';

export async function POST(request: NextRequest) {
  try {
    const { action, slugs, status } = await request.json();

    if (!slugs || !Array.isArray(slugs) || slugs.length === 0) {
      return NextResponse.json({ error: 'No slugs provided' }, { status: 400 });
    }

    const db = await getDb();

    if (action === 'status' && status) {
      await db.collection('articles').updateMany(
        { slug: { $in: slugs } },
        { $set: { status, updated_at: new Date() } }
      );
      return NextResponse.json({ success: true, count: slugs.length });
    }

    if (action === 'delete') {
      await db.collection('articles').deleteMany({ slug: { $in: slugs } });
      return NextResponse.json({ success: true, count: slugs.length });
    }

    return NextResponse.json({ error: 'Invalid action' }, { status: 400 });
  } catch (error) {
    console.error('Bulk action error:', error);
    return NextResponse.json({ error: (error as Error).message }, { status: 500 });
  }
}
