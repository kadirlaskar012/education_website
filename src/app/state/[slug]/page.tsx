import React from 'react';
import Link from 'next/link';
import type { Metadata } from 'next';
import { getArticlesByState, getSidebarNotices } from '@/lib/db';
import { Sidebar } from '@/components/Sidebar';

export const revalidate = 60;

interface StatePageProps {
  params: Promise<{ slug: string }>;
  searchParams: Promise<{ page?: string }>;
}

const ALL_STATES: Record<string, string> = {
  'all-india': 'All India / National',
  'central-govt': 'Central Government',
  'west-bengal': 'West Bengal (WBPSC)',
  'uttar-pradesh': 'Uttar Pradesh (UPPSC)',
  'bihar': 'Bihar (BPSC)',
  'rajasthan': 'Rajasthan (RPSC)',
  'madhya-pradesh': 'Madhya Pradesh (MPPSC)',
  'maharashtra': 'Maharashtra (MPSC)',
};

export async function generateMetadata({ params }: StatePageProps): Promise<Metadata> {
  const { slug } = await params;
  const stateName = ALL_STATES[slug] || slug.replace(/-/g, ' ').toUpperCase();

  return {
    title: `${stateName} Government Job & Exam Notifications`,
    description: `Official verified ${stateName} recruitment notices, exam dates, admit cards, and results.`,
    alternates: {
      canonical: `/state/${slug}`,
    },
  };
}

export default async function StatePage({ params, searchParams }: StatePageProps) {
  const { slug } = await params;
  const { page = '1' } = await searchParams;

  const currentPage = Math.max(1, parseInt(page, 10) || 1);
  const stateName = ALL_STATES[slug] || slug.replace(/-/g, ' ').replace(/\b\w/g, (l) => l.toUpperCase());

  const [{ articles, total, totalPages }, sidebarNotices] = await Promise.all([
    getArticlesByState(slug, currentPage, 12),
    getSidebarNotices(10),
  ]);

  return (
    <>
      {/* Breadcrumb */}
      <nav className="breadcrumb-nav" aria-label="Breadcrumb">
        <Link href="/">Home</Link>
        <span className="separator">/</span>
        <Link href="/#state-matrix">States</Link>
        <span className="separator">/</span>
        <span>{stateName}</span>
      </nav>

      {/* State Header Banner */}
      <header className="category-header-banner">
        <div className="cat-header-top">
          <h1 className="cat-header-title">
            🏛️ {stateName} Notifications
          </h1>
          <span className="cat-header-count">
            Total Updates: {total}
          </span>
        </div>
        <p className="cat-header-desc">
          Verified official notifications, state recruitment exams, results, admit cards, and board updates for {stateName}.
        </p>

        {/* State Quick Chips */}
        <div className="state-chips-row" style={{ marginTop: '1rem', display: 'flex', flexWrap: 'wrap', gap: '0.5rem' }}>
          {Object.entries(ALL_STATES).map(([sSlug, sName]) => (
            <Link
              key={sSlug}
              href={`/state/${sSlug}`}
              className={`smart-tab-pill ${sSlug === slug ? 'active' : ''}`}
              style={{ fontSize: '0.75rem', textDecoration: 'none' }}
            >
              {sName}
            </Link>
          ))}
        </div>
      </header>

      {/* Main Feed Layout */}
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
                        <span className="official-verified-badge">
                          🏛️ {art.official_source_name || art.state_name}
                        </span>
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
                        {art.excerpt || 'Click to view complete official notification details and direct application links.'}
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
                  {Array.from({ length: totalPages }, (_, i) => i + 1).map((pageNum) => (
                    <Link
                      key={pageNum}
                      href={`/state/${slug}?page=${pageNum}`}
                      className={`pagination-item ${pageNum === currentPage ? 'active' : ''}`}
                    >
                      {pageNum}
                    </Link>
                  ))}
                </div>
              )}
            </>
          ) : (
            <div className="empty-state-box">
              <div className="empty-icon">📭</div>
              <h3>No active notices found for {stateName}</h3>
              <p>New recruitment and exam notifications are being synchronized by the automated pipeline.</p>
              <Link
                href="/"
                className="admin-btn admin-btn-primary"
                style={{ marginTop: '1rem', display: 'inline-block' }}
              >
                Return to All Updates
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
