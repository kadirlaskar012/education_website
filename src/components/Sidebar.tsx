import React from 'react';
import Link from 'next/link';
import { Article } from '@/types';

interface SidebarProps {
  latestNotices: Article[];
}

export function Sidebar({ latestNotices }: SidebarProps) {
  return (
    <aside className="feed-sidebar-col" aria-label="Official Notices Sidebar">
      {/* 1. Latest 10 Notices */}
      {latestNotices && latestNotices.length > 0 && (
        <div className="sidebar-card" style={{ marginBottom: '1.5rem' }}>
          <div className="sidebar-header-row">
            <h3 className="sidebar-title">
              <span>📢</span> Latest 10 Notices
            </h3>
          </div>
          <div className="sidebar-notices-list">
            {latestNotices.slice(0, 10).map((notice) => {
              const pubDate = new Date(notice.published_at);
              const formattedDate = pubDate.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
              });

              return (
                <Link
                  key={notice.slug}
                  href={`/news/${notice.slug}`}
                  className="sidebar-notice-item"
                >
                  <div className="sb-notice-badge-row">
                    <span className="sb-badge-tag">{notice.category_name}</span>
                    <span className="sb-time-text">{formattedDate}</span>
                  </div>
                  <h4 className="sb-notice-title">{notice.title}</h4>
                </Link>
              );
            })}
          </div>
        </div>
      )}

      {/* 2. Quick Categories */}
      <div className="sidebar-card" style={{ marginBottom: '1.5rem' }}>
        <h3 className="sidebar-title">
          <span>📂</span> Quick Categories
        </h3>
        <ul className="sidebar-links-list">
          <li><Link href="/results">📋 Results & Merit Lists</Link></li>
          <li><Link href="/admit-card">🎫 Admit Cards & Slips</Link></li>
          <li><Link href="/recruitment">💼 Government Recruitment</Link></li>
          <li><Link href="/exam">📝 Exam Calendar & Dates</Link></li>
          <li><Link href="/answer-key">🔑 Answer Keys</Link></li>
          <li><Link href="/category/scholarship">🏆 Scholarships & Grants</Link></li>
          <li><Link href="/category/admission">🎓 Admission & Counseling</Link></li>
          <li><Link href="/category/board-exams">🏫 Board Exams (CBSE/ICSE)</Link></li>
        </ul>
      </div>

      {/* 3. State Portals */}
      <div className="sidebar-card" style={{ marginBottom: '1.5rem' }}>
        <h3 className="sidebar-title">
          <span>🗺️</span> State Portals
        </h3>
        <ul className="sidebar-links-list">
          <li><Link href="/state/central-govt">🏛️ Central Government</Link></li>
          <li><Link href="/state/west-bengal">🌊 West Bengal (WBPSC)</Link></li>
          <li><Link href="/state/uttar-pradesh">🌾 Uttar Pradesh (UPPSC)</Link></li>
          <li><Link href="/state/bihar">🚩 Bihar (BPSC)</Link></li>
          <li><Link href="/state/rajasthan">🏰 Rajasthan (RPSC)</Link></li>
          <li><Link href="/state/madhya-pradesh">🌲 Madhya Pradesh (MPPSC)</Link></li>
          <li><Link href="/state/maharashtra">🏙️ Maharashtra (MPSC)</Link></li>
          <li><Link href="/state/all-india">🇮🇳 All India Level</Link></li>
        </ul>
      </div>

      {/* 4. Official Government Portals Monitored (Professional, No Scraped Label) */}
      <div className="sidebar-card" style={{ marginBottom: '1.5rem' }}>
        <h3 className="sidebar-title">
          <span>🏛️</span> Official Portals Monitored
        </h3>
        <div style={{ display: 'flex', flexDirection: 'column', gap: '0.45rem', fontSize: '0.785rem' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', padding: '0.35rem 0', borderBottom: '1px solid var(--color-border)' }}>
            <span style={{ fontWeight: 600 }}>UPSC Civil Services</span>
            <span style={{ color: '#16a34a', fontWeight: 700 }}>✓ Verified</span>
          </div>
          <div style={{ display: 'flex', justifyContent: 'space-between', padding: '0.35rem 0', borderBottom: '1px solid var(--color-border)' }}>
            <span style={{ fontWeight: 600 }}>Staff Selection (SSC)</span>
            <span style={{ color: '#16a34a', fontWeight: 700 }}>✓ Verified</span>
          </div>
          <div style={{ display: 'flex', justifyContent: 'space-between', padding: '0.35rem 0', borderBottom: '1px solid var(--color-border)' }}>
            <span style={{ fontWeight: 600 }}>Railway RRB</span>
            <span style={{ color: '#16a34a', fontWeight: 700 }}>✓ Verified</span>
          </div>
          <div style={{ display: 'flex', justifyContent: 'space-between', padding: '0.35rem 0', borderBottom: '1px solid var(--color-border)' }}>
            <span style={{ fontWeight: 600 }}>NTA (NEET/JEE/CUET)</span>
            <span style={{ color: '#16a34a', fontWeight: 700 }}>✓ Verified</span>
          </div>
          <div style={{ display: 'flex', justifyContent: 'space-between', padding: '0.35rem 0' }}>
            <span style={{ fontWeight: 600 }}>CBSE / State Boards</span>
            <span style={{ color: '#16a34a', fontWeight: 700 }}>✓ Verified</span>
          </div>
        </div>
      </div>

      {/* 5. Official Verification Trust Box */}
      <div className="sidebar-card official-verify-box" style={{ background: 'var(--color-bg)', borderLeft: '4px solid #2563eb' }}>
        <h3 className="sidebar-title" style={{ fontSize: '0.85rem' }}>
          🛡️ Official Notice Authenticity
        </h3>
        <p style={{ fontSize: '0.75rem', color: 'var(--color-text-muted)', lineHeight: 1.5, marginTop: '0.35rem' }}>
          All educational notifications, admit cards, and merit lists published on EduGov News are directly referenced from respective official public authority portals.
        </p>
      </div>
    </aside>
  );
}
