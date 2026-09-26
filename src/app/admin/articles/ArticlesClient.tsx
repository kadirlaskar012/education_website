'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { Article } from '@/types';

interface ArticlesClientProps {
  initialArticles: Article[];
  total: number;
}

export function ArticlesClient({ initialArticles, total }: ArticlesClientProps) {
  const [articles, setArticles] = useState<Article[]>(initialArticles);
  const [selectedSlugs, setSelectedSlugs] = useState<string[]>([]);
  const [filterCategory, setFilterCategory] = useState('all');
  const [filterStatus, setFilterStatus] = useState('all');
  const [search, setSearch] = useState('');
  const [isProcessing, setIsProcessing] = useState(false);

  // Toggle selection
  const toggleSelectAll = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.checked) {
      setSelectedSlugs(filteredArticles.map((a) => a.slug));
    } else {
      setSelectedSlugs([]);
    }
  };

  const toggleSelect = (slug: string) => {
    setSelectedSlugs((prev) =>
      prev.includes(slug) ? prev.filter((s) => s !== slug) : [...prev, slug]
    );
  };

  // Filtered in-memory list
  const filteredArticles = articles.filter((art) => {
    const matchCat = filterCategory === 'all' || art.category_slug === filterCategory;
    const matchStatus = filterStatus === 'all' || art.status === filterStatus;
    const matchSearch =
      !search.trim() ||
      art.title.toLowerCase().includes(search.toLowerCase()) ||
      (art.official_source_name && art.official_source_name.toLowerCase().includes(search.toLowerCase()));
    return matchCat && matchStatus && matchSearch;
  });

  // Bulk status update action
  const handleBulkStatus = async (newStatus: 'published' | 'draft' | 'archived') => {
    if (selectedSlugs.length === 0) return;
    setIsProcessing(true);

    try {
      const res = await fetch('/api/admin/bulk-action', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          action: 'status',
          slugs: selectedSlugs,
          status: newStatus,
        }),
      });

      if (res.ok) {
        setArticles((prev) =>
          prev.map((a) => (selectedSlugs.includes(a.slug) ? { ...a, status: newStatus } : a))
        );
        setSelectedSlugs([]);
      }
    } catch (err) {
      console.error('Bulk action error:', err);
    } finally {
      setIsProcessing(false);
    }
  };

  // Bulk delete action
  const handleBulkDelete = async () => {
    if (selectedSlugs.length === 0) return;
    if (!confirm(`Are you sure you want to delete ${selectedSlugs.length} selected notices?`)) return;
    setIsProcessing(true);

    try {
      const res = await fetch('/api/admin/bulk-action', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          action: 'delete',
          slugs: selectedSlugs,
        }),
      });

      if (res.ok) {
        setArticles((prev) => prev.filter((a) => !selectedSlugs.includes(a.slug)));
        setSelectedSlugs([]);
      }
    } catch (err) {
      console.error('Bulk delete error:', err);
    } finally {
      setIsProcessing(false);
    }
  };

  return (
    <div>
      <div className="admin-header-row">
        <div>
          <h1 style={{ fontSize: '1.4rem', fontWeight: 800, color: 'var(--color-text-main)', margin: 0 }}>
            Education Notices Management
          </h1>
          <p style={{ fontSize: '0.8125rem', color: 'var(--color-text-muted)', marginTop: '0.25rem' }}>
            Filter, review, edit, select multiple posts, and audit all official &amp; generated education notices
          </p>
        </div>
      </div>

      {/* Filter & Multi-Select Control Bar */}
      <div
        className="admin-card"
        style={{
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          flexWrap: 'wrap',
          gap: '1rem',
          padding: '0.85rem 1.25rem',
        }}
      >
        <div style={{ display: 'flex', gap: '0.5rem', flexWrap: 'wrap', alignItems: 'center' }}>
          <input
            type="text"
            placeholder="Search headline or portal..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            style={{
              padding: '0.45rem 0.75rem',
              borderRadius: '6px',
              border: '1px solid var(--color-border)',
              background: 'var(--color-bg)',
              fontSize: '0.8125rem',
              color: 'var(--color-text-main)',
              width: '220px',
            }}
          />

          <select
            value={filterCategory}
            onChange={(e) => setFilterCategory(e.target.value)}
            style={{
              padding: '0.45rem 0.75rem',
              borderRadius: '6px',
              border: '1px solid var(--color-border)',
              background: 'var(--color-bg)',
              fontSize: '0.8125rem',
              color: 'var(--color-text-main)',
            }}
          >
            <option value="all">All Categories</option>
            <option value="results">Results</option>
            <option value="admit-card">Admit Card</option>
            <option value="recruitment">Recruitment</option>
            <option value="exam">Exam Dates</option>
            <option value="scholarship">Scholarships</option>
          </select>

          <select
            value={filterStatus}
            onChange={(e) => setFilterStatus(e.target.value)}
            style={{
              padding: '0.45rem 0.75rem',
              borderRadius: '6px',
              border: '1px solid var(--color-border)',
              background: 'var(--color-bg)',
              fontSize: '0.8125rem',
              color: 'var(--color-text-main)',
            }}
          >
            <option value="all">All Statuses</option>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
            <option value="in_review">In Review</option>
          </select>
        </div>

        {/* Multi-Select Bulk Actions Bar */}
        {selectedSlugs.length > 0 && (
          <div
            style={{
              display: 'flex',
              alignItems: 'center',
              gap: '0.5rem',
              background: '#eff6ff',
              padding: '0.4rem 0.75rem',
              borderRadius: '6px',
              border: '1px solid #bfdbfe',
            }}
          >
            <span style={{ fontSize: '0.75rem', fontWeight: 700, color: '#1e3a8a' }}>
              Selected: {selectedSlugs.length}
            </span>
            <button
              type="button"
              disabled={isProcessing}
              onClick={() => handleBulkStatus('published')}
              className="admin-btn-sm"
              style={{ background: '#16a34a', color: '#fff', border: 'none', cursor: 'pointer' }}
            >
              Publish
            </button>
            <button
              type="button"
              disabled={isProcessing}
              onClick={() => handleBulkStatus('draft')}
              className="admin-btn-sm"
              style={{ background: '#d97706', color: '#fff', border: 'none', cursor: 'pointer' }}
            >
              Draft
            </button>
            <button
              type="button"
              disabled={isProcessing}
              onClick={handleBulkDelete}
              className="admin-btn-sm"
              style={{ background: '#dc2626', color: '#fff', border: 'none', cursor: 'pointer' }}
            >
              Delete
            </button>
          </div>
        )}
      </div>

      {/* Notices Table */}
      <div className="admin-card">
        <div style={{ overflowX: 'auto' }}>
          <table className="admin-table">
            <thead>
              <tr>
                <th style={{ width: '40px' }}>
                  <input
                    type="checkbox"
                    onChange={toggleSelectAll}
                    checked={
                      filteredArticles.length > 0 &&
                      selectedSlugs.length === filteredArticles.length
                    }
                  />
                </th>
                <th>Headline</th>
                <th>Category</th>
                <th>State</th>
                <th>Status</th>
                <th>Published Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              {filteredArticles.map((art) => {
                const isSelected = selectedSlugs.includes(art.slug);
                return (
                  <tr
                    key={art.slug}
                    style={{ background: isSelected ? 'rgba(37,99,235,0.06)' : undefined }}
                  >
                    <td>
                      <input
                        type="checkbox"
                        checked={isSelected}
                        onChange={() => toggleSelect(art.slug)}
                      />
                    </td>
                    <td style={{ fontWeight: 600, maxWidth: '380px' }}>
                      <Link
                        href={`/news/${art.slug}`}
                        target="_blank"
                        style={{ color: '#2563eb', textDecoration: 'none' }}
                      >
                        {art.title}
                      </Link>
                      {art.official_source_name && (
                        <div style={{ fontSize: '0.7rem', color: '#64748b' }}>
                          🏛️ {art.official_source_name}
                        </div>
                      )}
                    </td>
                    <td>
                      <span className="cat-badge-micro">{art.category_name}</span>
                    </td>
                    <td style={{ fontSize: '0.75rem' }}>{art.state_name || art.state}</td>
                    <td>
                      <span className={`status-badge status-${art.status}`}>
                        {art.status.toUpperCase()}
                      </span>
                    </td>
                    <td style={{ fontSize: '0.75rem' }}>
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
                );
              })}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
