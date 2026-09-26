'use client';

import React, { useRef, useEffect } from 'react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';

const TABS = [
  { label: 'All', icon: '🏠', href: '/' },
  { label: 'Results', icon: '📋', href: '/results' },
  { label: 'Admit Cards', icon: '🎫', href: '/admit-card' },
  { label: 'Recruitment', icon: '💼', href: '/recruitment' },
  { label: 'Exams', icon: '📝', href: '/exam' },
  { label: 'Answer Key', icon: '🔑', href: '/answer-key' },
  { label: 'Scholarships', icon: '🏆', href: '/category/scholarship' },
  { label: 'JEE / NEET', icon: '🎯', href: '/category/entrance-exams' },
];

export function MobileTabs() {
  const pathname = usePathname();
  const trackRef = useRef<HTMLDivElement>(null);

  // Auto-scroll active tab into view
  useEffect(() => {
    if (trackRef.current) {
      const activeEl = trackRef.current.querySelector('.active') as HTMLElement | null;
      if (activeEl) {
        const trackRect = trackRef.current.getBoundingClientRect();
        const tabRect = activeEl.getBoundingClientRect();
        const scrollLeft = activeEl.offsetLeft - (trackRect.width / 2) + (tabRect.width / 2);
        trackRef.current.scrollTo({
          left: Math.max(0, scrollLeft),
          behavior: 'smooth',
        });
      }
    }
  }, [pathname]);

  return (
    <div className="mobile-smart-tabs-bar">
      <div className="smart-tabs-scroll-track" ref={trackRef} id="categoryScrollTrack">
        {TABS.map((tab) => {
          const isActive = tab.href === '/' ? pathname === '/' : pathname.startsWith(tab.href);
          return (
            <Link
              key={tab.href}
              href={tab.href}
              className={`smart-tab-pill ${isActive ? 'active' : ''}`}
            >
              <span>{tab.icon}</span> {tab.label}
            </Link>
          );
        })}
      </div>
    </div>
  );
}
