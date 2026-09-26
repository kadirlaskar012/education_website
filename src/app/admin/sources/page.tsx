import React from 'react';
import { getSources } from '@/lib/db';
import { SourcesClient } from './SourcesClient';

export const revalidate = 0; // Dynamic

export default async function AdminSourcesPage() {
  const sources = await getSources();
  return <SourcesClient sources={sources} />;
}
