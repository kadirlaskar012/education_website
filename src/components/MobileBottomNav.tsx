'use client';

import React from 'react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { useUI } from './UIContext';
import { Home, FileText, CreditCard, Briefcase, LayoutGrid } from 'lucide-react';

export function MobileBottomNav() {
  const pathname = usePathname();
  const { openDrawer } = useUI();

  return (
    <nav className="mobile-bottom-nav" aria-label="Mobile Bottom Navigation">
      <div className="bottom-nav-inner">
        <Link
          href="/"
          className={`bottom-nav-item ${pathname === '/' ? 'active' : ''}`}
        >
          <span className="nav-icon">
            <Home size={20} />
          </span>
          <span className="nav-text">Home</span>
        </Link>

        <Link
          href="/results"
          className={`bottom-nav-item ${pathname.startsWith('/results') ? 'active' : ''}`}
        >
          <span className="nav-icon">
            <FileText size={20} />
          </span>
          <span className="nav-text">Results</span>
        </Link>

        <Link
          href="/admit-card"
          className={`bottom-nav-item ${pathname.startsWith('/admit-card') ? 'active' : ''}`}
        >
          <span className="nav-icon">
            <CreditCard size={20} />
          </span>
          <span className="nav-text">Admit Card</span>
        </Link>

        <Link
          href="/recruitment"
          className={`bottom-nav-item ${pathname.startsWith('/recruitment') ? 'active' : ''}`}
        >
          <span className="nav-icon">
            <Briefcase size={20} />
          </span>
          <span className="nav-text">Jobs</span>
        </Link>

        <button
          type="button"
          className="bottom-nav-item nav-item-explore"
          onClick={openDrawer}
          aria-label="Open Category Explorer Drawer"
        >
          <span className="nav-icon icon-explore">
            <LayoutGrid size={20} />
          </span>
          <span className="nav-text">Explore</span>
        </button>
      </div>
    </nav>
  );
}
