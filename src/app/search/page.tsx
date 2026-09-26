import React from 'react';
import Link from 'next/link';
import type { Metadata } from 'next';
import { searchArticles, getSidebarNotices } from '@/lib/db';
import { Sidebar } from '@/components/Sidebar';

export const revalidate = 0; // Dynamic search

interface SearchPageProps {
  searchParams: Promise<{ q?: string; page?: string }>;
}

export async function generateMetadata({ searchParams }: SearchPageProps): Promise<Metadata> {
  const { q = '' } = await searchParams;
  return {
    title: q ? `Search results for "${q}"` : 'Search Official Education Notices',
    description: `Search verified government job alerts, admit cards, exam schedules, and results for ${q || 'all exams'}.`,
  };
}

export default async function SearchPage({ searchParams }: SearchPageProps) {
  const { q = '', page = '1' } = await searchParams;
  const query = q.trim();
  const currentPage = Math.max(1, parseInt(page, 10) || 1);

  const [{ articles, total, totalPages }, sidebarNotices] = await Promise.all([
    searchArticles(query, currentPage, 12),
    getSidebarNotices(10),
  ]);

  return (
    <>
      {/* Breadcrumb Navigation */}
      <nav className="breadcrumb-nav" aria-label="Breadcrumb">
        <Link href="/">Home</Link>
        <span className="separator">/</span>
        <span>Search</span>
      </nav>

      {/* Search Header Banner */}
      <header className="category-header-banner">
        <div className="cat-header-top">
          <h1 className="cat-header-title">
            🔍 Search: {query ? query : 'All Notices'}
          </h1>
          <span className="cat-header-count">
            Found {total} Results
          </span>
        </div>

        {/* Search Input Form */}
        <div style={{ marginTop: '1rem' }}>
          <form action="/search" method="get" className="search-form" style={{ maxWidth: '550px' }}>
            <input
              type="text"
              name="q"
              defaultValue={query}
              placeholder="Enter keywords (e.g. SSC, UPSC, Admit Card, Result)..."
              required
            />
            <button type="submit">🔍 Search</button>
          </form>
        </div>
      </header>

      {/* Main Feed & Sidebar Grid */}
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
                  {Array.from({ length: totalPages }, (_, i) => i + 1).map((pageNum) => (
                    <Link
                      key={pageNum}
                      href={`/search?q=${encodeURIComponent(query)}&page=${pageNum}`}
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
              <div className="empty-icon">🔍</div>
              <h3>No matching education notices found</h3>
              <p>Try searching with different keywords like "SSC", "UPSC", "Admit Card", or "Result".</p>
              <Link
                href="/"
                className="admin-btn admin-btn-primary"
                style={{ marginTop: '1rem', display: 'inline-block' }}
              >
                Return to Homepage
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
