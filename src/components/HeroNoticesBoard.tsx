import React from 'react';
import Link from 'next/link';
import { Article } from '@/types';

interface HeroNoticesBoardProps {
  notices: Article[];
}

export function HeroNoticesBoard({ notices }: HeroNoticesBoardProps) {
  if (!notices || notices.length === 0) return null;

  return (
    <section className="hero-notices-board">
      <div className="hero-notices-header">
        <div className="notices-header-left">
          <span className="live-pulse-icon">🔴</span>
          <h1 className="hero-notices-title">Latest Official Notices (Top 10 Updates)</h1>
        </div>
        <div className="notices-header-badge">
          ⚡ Real-Time Government Feed
        </div>
      </div>

      <div className="hero-notices-list">
        {notices.slice(0, 10).map((notice, index) => {
          const rank = String(index + 1).padStart(2, '0');
          const pubDate = new Date(notice.published_at);
          const formattedDate = pubDate.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
          }) + ' — ' + pubDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });

          return (
            <div key={notice.slug} className="hero-notice-row">
              <div className="notice-num">#{rank}</div>
              <div className="notice-details">
                <div className="notice-meta-tags">
                  <span className="cat-badge-text">{notice.category_name}</span>
                  {notice.official_source_name && (
                    <span className="authority-badge-text">
                      🏛️ {notice.official_source_name}
                    </span>
                  )}
                  <time className="notice-time-text">
                    📅 {formattedDate}
                  </time>
                </div>
                <h2 className="notice-text-headline">
                  <Link href={`/news/${notice.slug}`}>
                    {notice.title}
                  </Link>
                </h2>
              </div>
              <div className="notice-action-col">
                <Link href={`/news/${notice.slug}`} className="btn-notice-read">
                  Read Notice →
                </Link>
              </div>
            </div>
          );
        })}
      </div>
    </section>
  );
}
