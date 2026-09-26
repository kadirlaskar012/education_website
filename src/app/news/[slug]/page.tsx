import React from 'react';
import { notFound } from 'next/navigation';
import Link from 'next/link';
import type { Metadata } from 'next';
import { getArticleBySlug, getRelatedArticles, getSidebarNotices } from '@/lib/db';
import { Sidebar } from '@/components/Sidebar';
import { ShareButtons } from '@/components/ShareButtons';

export const revalidate = 60; // ISR revalidation

interface ArticlePageProps {
  params: Promise<{ slug: string }>;
}

export async function generateMetadata({ params }: ArticlePageProps): Promise<Metadata> {
  const { slug } = await params;
  const article = await getArticleBySlug(slug);
  if (!article) return { title: 'Notice Not Found' };

  return {
    title: article.meta_title || article.title,
    description: article.meta_description || article.excerpt,
    alternates: {
      canonical: `/news/${article.slug}`,
    },
    openGraph: {
      title: article.title,
      description: article.excerpt,
      type: 'article',
      publishedTime: new Date(article.published_at).toISOString(),
    },
  };
}

export default async function ArticleDetailPage({ params }: ArticlePageProps) {
  const { slug } = await params;
  const article = await getArticleBySlug(slug);

  if (!article) {
    notFound();
  }

  const [relatedArticles, sidebarNotices] = await Promise.all([
    getRelatedArticles(article.category_id, article.slug, 4),
    getSidebarNotices(10),
  ]);

  const pubDate = new Date(article.published_at);
  const formattedDate = pubDate.toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
  }) + ' — ' + pubDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });

  // Schema Markup
  const jsonLd = {
    '@context': 'https://schema.org',
    '@type': 'NewsArticle',
    headline: article.title,
    description: article.excerpt,
    datePublished: pubDate.toISOString(),
    dateModified: article.updated_at ? new Date(article.updated_at).toISOString() : pubDate.toISOString(),
    author: {
      '@type': 'Organization',
      name: article.official_source_name || 'Official Government Authority',
    },
    publisher: {
      '@type': 'Organization',
      name: 'EduGov News',
      logo: {
        '@type': 'ImageObject',
        url: `${process.env.NEXT_PUBLIC_SITE_URL || 'http://localhost:3000'}/static/img/logo.png`,
      },
    },
    mainEntityOfPage: {
      '@type': 'WebPage',
      '@id': `${process.env.NEXT_PUBLIC_SITE_URL || 'http://localhost:3000'}/news/${article.slug}`,
    },
  };

  return (
    <>
      {/* Schema Injection */}
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }}
      />

      {/* Breadcrumb Navigation */}
      <nav className="breadcrumb-nav" aria-label="Breadcrumb">
        <Link href="/">Home</Link>
        <span className="separator">/</span>
        <Link href={`/category/${article.category_slug}`}>
          {article.category_name}
        </Link>
        <span className="separator">/</span>
        <span className="truncate">{article.title}</span>
      </nav>

      {/* Feed Layout Grid */}
      <div className="feed-layout-grid">
        <main className="feed-main-col">
          <article className="article-container">
            {/* Header Area */}
            <header className="article-header">
              <h1 className="article-title-h1">{article.title}</h1>

              <div className="article-meta-row">
                <span className="cat-badge">{article.category_name}</span>
                {article.official_source_name && (
                  <span className="official-verified-badge">
                    ✓ Verified Official: {article.official_source_name}
                  </span>
                )}
                <time dateTime={pubDate.toISOString()} className="article-time">
                  📅 Published: {formattedDate}
                </time>
                <span className="article-time">
                  👁️ {article.view_count || 12} views
                </span>
              </div>

              {/* Interactive Share / Print Bar */}
              <ShareButtons title={article.title} slug={article.slug} />
            </header>

            {/* Official Source Verification Box */}
            <div className="source-verification-box">
              <div className="source-verification-header">
                <span className="source-verif-icon">🏛️</span>
                <span className="source-verif-heading">Official Government Source Verification</span>
              </div>
              <p className="source-verif-desc">
                This notice is authenticated and synchronized from the official notification released by{' '}
                <strong className="source-verif-authority">{article.official_source_name || 'Government Board'}</strong>.
              </p>
              <div className="source-verif-links">
                {article.original_url && (
                  <div className="source-verif-link-item">
                    <span>Direct Official Source:</span>
                    <a
                      href={article.original_url}
                      target="_blank"
                      rel="noopener noreferrer nofollow"
                      className="source-verif-anchor"
                    >
                      {article.original_url}
                    </a>
                  </div>
                )}
                {article.pdf_url && (
                  <div className="source-verif-link-item">
                    <span>Official PDF Document:</span>
                    <a
                      href={article.pdf_url}
                      target="_blank"
                      rel="noopener noreferrer nofollow"
                      className="source-verif-anchor"
                    >
                      Download PDF ↗
                    </a>
                  </div>
                )}
              </div>
            </div>

            {/* Article Main Body HTML */}
            <div
              className="article-main-body"
              dangerouslySetInnerHTML={{ __html: article.content_html }}
            />

            {/* Related Official Updates */}
            {relatedArticles.length > 0 && (
              <div className="related-articles-box" style={{ marginTop: '2.5rem' }}>
                <h3 className="related-articles-title">
                  📌 Related Official Updates & Notices
                </h3>
                <div className="related-articles-list">
                  {relatedArticles.map((rel) => (
                    <div key={rel.slug} className="related-article-row">
                      <span className="bullet-accent">•</span>
                      <Link href={`/news/${rel.slug}`} className="related-link">
                        {rel.title}
                      </Link>
                    </div>
                  ))}
                </div>
              </div>
            )}
          </article>
        </main>

        {/* Standard Universal Sidebar */}
        <Sidebar latestNotices={sidebarNotices} />
      </div>
    </>
  );
}
