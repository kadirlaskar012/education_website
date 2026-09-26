import React from 'react';
import Link from 'next/link';

export default function AdminLayout({ children }: { children: React.ReactNode }) {
  return (
    <div className="admin-wrapper" style={{ marginTop: '1rem', minHeight: '80vh' }}>
      <header
        style={{
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          background: 'var(--color-surface)',
          border: '1px solid var(--color-border)',
          borderRadius: '8px',
          padding: '0.85rem 1.25rem',
          marginBottom: '1.5rem',
          flexWrap: 'wrap',
          gap: '0.75rem',
        }}
      >
        <div style={{ display: 'flex', alignItems: 'center', gap: '1rem' }}>
          <Link href="/admin" style={{ fontSize: '1.1rem', fontWeight: 800, color: 'var(--color-text-main)' }}>
            🏛️ EduGov <span style={{ color: '#2563eb' }}>Administration</span>
          </Link>
          <nav style={{ display: 'flex', gap: '0.5rem' }}>
            <Link href="/admin" className="admin-btn-sm" style={{ textDecoration: 'none' }}>
              📊 Dashboard
            </Link>
            <Link href="/admin/articles" className="admin-btn-sm" style={{ textDecoration: 'none' }}>
              📑 All Notices
            </Link>
            <Link href="/admin/sources" className="admin-btn-sm" style={{ textDecoration: 'none' }}>
              📡 Scrapers
            </Link>
            <Link href="/admin/settings" className="admin-btn-sm" style={{ textDecoration: 'none' }}>
              ⚙️ Settings
            </Link>
          </nav>
        </div>
        <div style={{ display: 'flex', gap: '0.5rem', alignItems: 'center' }}>
          <Link href="/" target="_blank" className="admin-btn admin-btn-secondary" style={{ fontSize: '0.75rem' }}>
            View Live Site ↗
          </Link>
        </div>
      </header>

      {children}
    </div>
  );
}
