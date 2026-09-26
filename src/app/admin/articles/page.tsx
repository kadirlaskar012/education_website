import React from 'react';
import { getDb } from '@/lib/mongodb';
import { serializeDoc } from '@/lib/db';
import { ArticlesClient } from './ArticlesClient';
import { Article } from '@/types';

export const revalidate = 0; // Dynamic

export default async function AdminArticlesPage() {
  const db = await getDb();
  const articles = await db
    .collection('articles')
    .find({})
    .sort({ published_at: -1 })
    .limit(100)
    .toArray();

  const total = await db.collection('articles').countDocuments({});

  const serialized = articles.map((a) => serializeDoc<Article>(a));

  return <ArticlesClient initialArticles={serialized} total={total} />;
}
