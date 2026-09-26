import CategoryPage, { generateMetadata as generateCatMetadata } from '../category/[slug]/page';
import type { Metadata } from 'next';

export const revalidate = 60;

export async function generateMetadata(): Promise<Metadata> {
  return generateCatMetadata({ params: Promise.resolve({ slug: 'answer-key' }) });
}

export default async function AnswerKeyHubPage({
  searchParams,
}: {
  searchParams: Promise<{ page?: string; state?: string }>;
}) {
  return CategoryPage({
    params: Promise.resolve({ slug: 'answer-key' }),
    searchParams,
  });
}
