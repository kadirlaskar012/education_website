import { NextRequest, NextResponse } from 'next/server';
import { getDb } from '@/lib/mongodb';

export async function POST(request: NextRequest) {
  try {
    const data = await request.json();
    const db = await getDb();

    for (const [key, value] of Object.entries(data)) {
      if (typeof value === 'string') {
        await db.collection('settings').updateOne(
          { key },
          { $set: { key, value, updated_at: new Date() } },
          { upsert: true }
        );
      }
    }

    return NextResponse.json({ success: true });
  } catch (error) {
    console.error('Settings save error:', error);
    return NextResponse.json({ error: (error as Error).message }, { status: 500 });
  }
}
