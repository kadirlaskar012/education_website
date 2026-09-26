import React from 'react';
import Link from 'next/link';
import type { Metadata } from 'next';
import { getArticlesByCategory, getSidebarNotices } from '@/lib/db';
import { Sidebar } from '@/components/Sidebar';
import { StateSelector } from '@/components/StateSelector';

export const revalidate = 60;

interface CategoryPageProps {
  params: Promise<{ slug: string }>;
  searchParams?: Promise<{ page?: string; state?: string }>;
}

export async function generateMetadata({ params }: CategoryPageProps): Promise<Metadata> {
  const { slug } = await params;
  const data = await getArticlesByCategory(slug, 1, 1);
  const catName = data.category ? data.category.name : slug.replace(/-/g, ' ').toUpperCase();

  return {
    title: `${catName} Notifications, Updates & Alerts`,
    description: `Official verified ${catName} news, examination schedules, merit lists, and recruitment alerts.`,
    alternates: {
      canonical: `/category/${slug}`,
    },
  };
}

export default async function CategoryPage({ params, searchParams }: CategoryPageProps) {
  const { slug } = await params;
  const sp = searchParams ? await searchParams : {};
  const { page = '1', state = '' } = sp;

  const currentPage = Math.max(1, parseInt(page, 10) || 1);
  const selectedState = state.trim();

  const [{ articles, total, totalPages, category }, sidebarNotices] = await Promise.all([
    getArticlesByCategory(slug, currentPage, 12, selectedState),
    getSidebarNotices(10),
  ]);

  const catName = category ? category.name : slug.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

  return (
    <>
      {/* Breadcrumb */}
      <nav className="breadcrumb-nav" aria-label="Breadcrumb">
        <Link href="/">Home</Link>
        <span className="separator">/</span>
        <Link href="/#category-matrix">Categories</Link>
        <span className="separator">/</span>
        <span>{catName}</span>
      </nav>

      {/* Category Header Banner */}
      <header className="category-header-banner">
        <div className="cat-header-top">
          <h1 className="cat-header-title">
            <span>{category?.icon || '📌'}</span> {catName}
          </h1>
          <span className="cat-header-count">
            {total} Updates
          </span>
        </div>
        <p className="cat-header-desc">
          {category?.description || `Verified educational notifications, official schedules, and recruitment updates for ${catName}.`}
        </p>

        {/* Collapsible State Selector Matrix */}
        <StateSelector
          categorySlug={slug}
          selectedState={selectedState}
          totalCount={total}
        />
      </header>

      {/* Main Feed Grid */}
      <div className="feed-layout-grid">
        <main className="feed-main-col">
          {articles.length > 0 ? (
            <>
              <div className="articles-stack">
                {articles.map((art) => {
                  const pubDate = new Date(art.published_at);
                  const formattedDate = pubDate.toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric',
                  }) + ' — ' + pubDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });

                  return (
                    <article key={art.slug} className="article-card">
                      <div className="card-meta">
                        <span className="cat-badge">{art.category_name}</span>
                        {art.official_source_name && (
                          <span className="official-verified-badge">
                            🏛️ {art.official_source_name}
                          </span>
                        )}
                        <time dateTime={pubDate.toISOString()} className="card-time">
                          {formattedDate}
                        </time>
                      </div>

                      <h2 className="card-title">
                        <Link href={`/news/${art.slug}`}>
                          {art.title}
                        </Link>
                      </h2>

                      <p className="card-excerpt">
                        {art.excerpt || 'Click to view complete details, official notification PDF, and direct application links.'}
                      </p>

                      <div className="card-footer">
                        <Link href={`/news/${art.slug}`} className="read-more-btn">
                          Read Full Notice & Direct Links »
                        </Link>
                        <span className="badge-verified-small">✓ Verified</span>
                      </div>
                    </article>
                  );
                })}
              </div>

              {/* Pagination */}
              {totalPages > 1 && (
                <div className="pagination-container">
                  {Array.from({ length: totalPages }, (_, i) => i + 1).map((pageNum) => {
                    const stateQuery = selectedState ? `&state=${encodeURIComponent(selectedState)}` : '';
                    return (
                      <Link
                        key={pageNum}
                        href={`/category/${slug}?page=${pageNum}${stateQuery}`}
                        className={`pagination-item ${pageNum === currentPage ? 'active' : ''}`}
                      >
                        {pageNum}
                      </Link>
                    );
                  })}
                </div>
              )}
            </>
          ) : (
            <div className="empty-state-box">
              <div className="empty-icon">📭</div>
              <h3>No notices found for this selection</h3>
              <p>New recruitment and exam notifications are synchronized daily.</p>
              <Link
                href={`/category/${slug}`}
                className="admin-btn admin-btn-primary"
                style={{ marginTop: '1rem', display: 'inline-block' }}
              >
                View All Regions
              </Link>
            </div>
          )}
        </main>

        {/* Standard Reusable Right Sidebar */}
        <Sidebar latestNotices={sidebarNotices} />
      </div>
    </>
  );
}
