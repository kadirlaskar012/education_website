import React from 'react';
import Link from 'next/link';
import { getDb } from '@/lib/mongodb';
import { serializeDoc, getAdminMetrics } from '@/lib/db';
import { Article } from '@/types';

export const revalidate = 0; // Dynamic

export default async function AdminDashboardPage() {
  const db = await getDb();
  const [totalArticles, publishedArticles, draftArticles, inReviewArticles, activeSources] =
    await Promise.all([
      db.collection('articles').countDocuments({}),
      db.collection('articles').countDocuments({ status: 'published' }),
      db.collection('articles').countDocuments({ status: 'draft' }),
      db.collection('articles').countDocuments({ status: 'in_review' }),
      db.collection('sources').countDocuments({ is_active: true }),
    ]);

  const rawArticles = await db
    .collection('articles')
    .find({})
    .sort({ published_at: -1 })
    .limit(10)
    .toArray();

  const recentArticles = rawArticles.map((a) => serializeDoc<Article>(a));

  return (
    <div>
      <div className="admin-header-row">
        <div>
          <h1 style={{ fontSize: '1.4rem', fontWeight: 800, color: 'var(--color-text-main)', margin: 0 }}>
            Automation & Ingestion Metrics
          </h1>
          <p style={{ fontSize: '0.8125rem', color: 'var(--color-text-muted)', marginTop: '0.25rem' }}>
            MongoDB Atlas connected • Real-time scraper status, AI generator & notice inventory
          </p>
        </div>
        <div style={{ display: 'flex', gap: '0.5rem' }}>
          <Link href="/admin/sources" className="admin-btn admin-btn-primary">
            ⚡ Run Scraper Batch
          </Link>
          <Link href="/admin/settings" className="admin-btn admin-btn-secondary">
            ⚙️ AI & Settings
          </Link>
        </div>
      </div>

      {/* Metrics Grid */}
      <div className="admin-metrics-grid">
        <div className="metric-card border-blue">
          <div className="metric-label">TOTAL NOTICES</div>
          <div className="metric-value">{totalArticles}</div>
        </div>
        <div className="metric-card border-green">
          <div className="metric-label">PUBLISHED</div>
          <div className="metric-value">{publishedArticles}</div>
        </div>
        <div className="metric-card border-amber">
          <div className="metric-label">PENDING REVIEW</div>
          <div className="metric-value">{inReviewArticles}</div>
        </div>
        <div className="metric-card border-slate">
          <div className="metric-label">DRAFTS</div>
          <div className="metric-value">{draftArticles}</div>
        </div>
        <div className="metric-card border-purple">
          <div className="metric-label">ACTIVE SCRAPERS</div>
          <div className="metric-value">{activeSources}</div>
        </div>
      </div>

      {/* Vercel Cron Configuration Notice */}
      <div
        className="admin-card"
        style={{
          background: 'var(--color-surface)',
          borderLeft: '4px solid #10b981',
          marginBottom: '1.5rem',
        }}
      >
        <h3 style={{ fontSize: '0.95rem', fontWeight: 700, margin: 0 }}>
          ⏰ Automated Vercel / Cloud Cron Configuration
        </h3>
        <p style={{ fontSize: '0.8rem', color: 'var(--color-text-muted)', margin: '0.5rem 0' }}>
          Vercel Cron is configured via <code>vercel.json</code> to trigger <code>/api/cron/scrape</code> automatically every 15 minutes.
        </p>
        <div
          style={{
            background: 'var(--color-bg)',
            padding: '0.6rem 0.85rem',
            borderRadius: '6px',
            fontFamily: 'var(--font-mono)',
            fontSize: '0.75rem',
            color: '#2563eb',
            border: '1px solid var(--color-border)',
          }}
        >
          Endpoint: GET /api/cron/scrape • Batch Size: 4 portals / run • Auto-Timeout Protected
        </div>
      </div>

      {/* Recent Notices Table */}
      <div className="admin-card">
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '1rem' }}>
          <h2 style={{ fontSize: '1.1rem', fontWeight: 800, margin: 0 }}>Recent Notices</h2>
          <Link href="/admin/articles" className="admin-btn-sm" style={{ textDecoration: 'none' }}>
            View All Notices ({totalArticles}) »
          </Link>
        </div>

        <div style={{ overflowX: 'auto' }}>
          <table className="admin-table">
            <thead>
              <tr>
                <th>Headline</th>
                <th>Category</th>
                <th>Status</th>
                <th>Views</th>
                <th>Published Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              {recentArticles.map((art) => (
                <tr key={art.slug}>
                  <td style={{ fontWeight: 600, maxWidth: '380px' }}>
                    <Link
                      href={`/news/${art.slug}`}
                      target="_blank"
                      style={{ color: '#2563eb', textDecoration: 'none' }}
                    >
                      {art.title}
                    </Link>
                  </td>
                  <td>
                    <span className="cat-badge-micro">{art.category_name}</span>
                  </td>
                  <td>
                    <span className={`status-badge status-${art.status}`}>
                      {art.status.toUpperCase()}
                    </span>
                  </td>
                  <td>{art.view_count || 0}</td>
                  <td>
                    {new Date(art.published_at).toLocaleDateString('en-US', {
                      month: 'short',
                      day: 'numeric',
                      year: 'numeric',
                    })}
                  </td>
                  <td>
                    <Link
                      href={`/news/${art.slug}`}
                      target="_blank"
                      className="admin-btn-sm"
                      style={{ textDecoration: 'none' }}
                    >
                      View ↗
                    </Link>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
