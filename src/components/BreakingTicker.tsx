'use client';

import React from 'react';
import Link from 'next/link';
import { Article } from '@/types';

interface BreakingTickerProps {
  articles: Article[];
}

export function BreakingTicker({ articles }: BreakingTickerProps) {
  if (!articles || articles.length === 0) return null;

  return (
    <div className="breaking-ticker-bar">
      <div className="site-container ticker-inner">
        <span className="ticker-badge">⚡ BREAKING</span>
        <div className="ticker-marquee">
          <div className="ticker-items">
            {/* Repeated for infinite loop */}
            {[...articles, ...articles].map((item, idx) => (
              <span key={`${item.slug}-${idx}`} className="ticker-item">
                <Link href={`/news/${item.slug}`}>
                  <span className="ticker-bullet">•</span> {item.title}
                </Link>
              </span>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
