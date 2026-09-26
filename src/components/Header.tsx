'use client';

import React, { useState, useRef, useEffect } from 'react';
import Link from 'next/link';
import { usePathname, useRouter } from 'next/navigation';
import { useUI } from './UIContext';
import { ThemeToggle } from './ThemeToggle';
import { Search, ChevronDown } from 'lucide-react';

export function Header() {
  const pathname = usePathname();
  const router = useRouter();
  const { openDrawer, openSearchModal } = useUI();
  const [searchQuery, setSearchQuery] = useState('');
  const [isDropdownOpen, setIsDropdownOpen] = useState(false);
  const dropdownRef = useRef<HTMLDivElement>(null);

  const handleSearchSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (searchQuery.trim()) {
      router.push(`/search?q=${encodeURIComponent(searchQuery.trim())}`);
    }
  };

  // Click outside to close dropdown
  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
        setIsDropdownOpen(false);
      }
    };
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  return (
    <>
      {/* Top Verified Trust Header (Desktop only) */}
      <div className="top-trust-bar">
        <div className="site-container trust-bar-inner">
          <div className="trust-left">
            <span className="trust-badge">🇮🇳 National Education & Recruitment Ingestion Network</span>
            <span className="trust-meta">Automated Official Synchronization Active</span>
          </div>
          <div className="trust-right">
            <ThemeToggle showLabel={true} />
            <Link href="/sitemap.xml" className="trust-link">Sitemap</Link>
            <Link href="/rss.xml" className="trust-link">RSS Feed</Link>
            <Link href="/admin" className="trust-link">Admin Access</Link>
          </div>
        </div>
      </div>

      {/* Main Site Header */}
      <header className="site-header">
        <div className="site-container header-inner">
          <div className="header-left">
            {/* Mobile Hamburger Menu */}
            <button
              type="button"
              className="mobile-menu-btn"
              onClick={openDrawer}
              aria-label="Open Navigation Drawer"
            >
              <span className="bar"></span>
              <span className="bar"></span>
              <span className="bar"></span>
            </button>

            {/* Brand Logo & Emblem */}
            <Link href="/" className="brand-logo" title="EduGov News Homepage">
              <div className="brand-logo-wrap">
                <div className="brand-emblem-icon">
                  <svg width="34" height="34" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="48" height="48" rx="12" fill="#2563eb" />
                    <path d="M24 10L6 20L24 30L42 20L24 10Z" fill="#ffffff" />
                    <path d="M12 24.5V33.5C12 33.5 16 38 24 38C32 38 36 33.5 36 33.5V24.5L24 31.5L12 24.5Z" fill="#dbeafe" />
                    <circle cx="24" cy="20" r="3" fill="#2563eb" />
                  </svg>
                </div>
                <div className="brand-text-stack">
                  <div className="brand-title">
                    <span className="logo-accent">EduGov</span>
                    <span className="logo-sub">News<span className="logo-dot">.</span></span>
                  </div>
                  <span className="logo-tagline">
                    <span className="live-dot"></span> OFFICIAL PORTAL
                  </span>
                </div>
              </div>
            </Link>
          </div>

          {/* Desktop Direct Search */}
          <div className="header-search-box">
            <form onSubmit={handleSearchSubmit} className="search-form" action="/search" method="GET">
              <input
                id="main-search-input"
                name="q"
                type="text"
                placeholder="Search exams, results, notifications..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                required
                aria-label="Search"
              />
              <button type="submit" aria-label="Submit search">
                <Search size={17} />
              </button>
            </form>
          </div>

          {/* Header Right Actions */}
          <div className="header-right-actions">
            <ThemeToggle showLabel={false} className="mobile-icon-btn" />
            <button
              id="mobileSearchTrigger"
              type="button"
              className="mobile-icon-btn"
              onClick={openSearchModal}
              aria-label="Search"
            >
              <Search size={16} />
            </button>
            <Link href="/recruitment" className="btn-gov-jobs">
              🏛️ Latest Jobs
            </Link>
          </div>
        </div>

        {/* Desktop Traditional Navigation Bar */}
        <nav className="desktop-main-nav">
          <div className="site-container nav-items-row">
            <Link href="/" className={`nav-item ${pathname === '/' ? 'active' : ''}`}>
              🏠 Home
            </Link>
            <Link href="/results" className={`nav-item ${pathname.startsWith('/results') ? 'active' : ''}`}>
              📋 Results
            </Link>
            <Link href="/admit-card" className={`nav-item ${pathname.startsWith('/admit-card') ? 'active' : ''}`}>
              🎫 Admit Card
            </Link>
            <Link href="/recruitment" className={`nav-item ${pathname.startsWith('/recruitment') ? 'active' : ''}`}>
              💼 Recruitment
            </Link>
            <Link href="/exam" className={`nav-item ${pathname.startsWith('/exam') ? 'active' : ''}`}>
              📝 Exam Dates
            </Link>
            <Link href="/answer-key" className={`nav-item ${pathname.startsWith('/answer-key') ? 'active' : ''}`}>
              🔑 Answer Key
            </Link>
            <Link href="/category/scholarship" className={`nav-item ${pathname.includes('scholarship') ? 'active' : ''}`}>
              🏆 Scholarship
            </Link>

            {/* Desktop Dropdown */}
            <div className={`nav-dropdown ${isDropdownOpen ? 'open' : ''}`} ref={dropdownRef}>
              <button
                type="button"
                className="nav-dropdown-btn"
                id="moreCategoriesBtn"
                onClick={() => setIsDropdownOpen(!isDropdownOpen)}
                aria-expanded={isDropdownOpen}
              >
                More Categories <ChevronDown size={14} />
              </button>
              <div className="nav-dropdown-menu">
                <Link href="/category/admission" onClick={() => setIsDropdownOpen(false)}>
                  🎓 Admission & Counseling
                </Link>
                <Link href="/category/application-form" onClick={() => setIsDropdownOpen(false)}>
                  📑 Application Forms
                </Link>
                <Link href="/category/board-exams" onClick={() => setIsDropdownOpen(false)}>
                  🏫 Board Exams (CBSE/ICSE)
                </Link>
                <Link href="/category/entrance-exams" onClick={() => setIsDropdownOpen(false)}>
                  🎯 Entrance Exams (JEE/NEET)
                </Link>
                <Link href="/category/government-jobs" onClick={() => setIsDropdownOpen(false)}>
                  🏛️ All Government Jobs
                </Link>
                <Link href="/category/important-updates" onClick={() => setIsDropdownOpen(false)}>
                  ⚡ Important Updates
                </Link>
              </div>
            </div>
          </div>
        </nav>
      </header>
    </>
  );
}
