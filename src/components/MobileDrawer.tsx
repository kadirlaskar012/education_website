'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { useUI } from './UIContext';
import { ThemeToggle } from './ThemeToggle';
import { Search, X } from 'lucide-react';

export function MobileDrawer() {
  const { isDrawerOpen, closeDrawer } = useUI();
  const [drawerSearch, setDrawerSearch] = useState('');
  const router = useRouter();

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    if (drawerSearch.trim()) {
      closeDrawer();
      router.push(`/search?q=${encodeURIComponent(drawerSearch.trim())}`);
    }
  };

  return (
    <>
      <div
        className={`drawer-backdrop ${isDrawerOpen ? 'active' : ''}`}
        onClick={closeDrawer}
      />
      <div className={`mobile-offcanvas-drawer ${isDrawerOpen ? 'open' : ''}`} id="mobileDrawer">
        <div className="drawer-handle-bar" onClick={closeDrawer}>
          <div className="handle-pill" />
        </div>

        <div className="drawer-header">
          <div className="drawer-title">
            <span className="logo-accent">EduGov</span>
            <span className="logo-sub">News<span className="logo-dot">.</span></span>
          </div>
          <div style={{ display: 'flex', gap: '0.5rem', alignItems: 'center' }}>
            <ThemeToggle showLabel={true} style={{ padding: '0.3rem 0.6rem' }} />
            <button
              type="button"
              className="drawer-close-btn"
              onClick={closeDrawer}
              aria-label="Close menu"
            >
              <X size={16} />
            </button>
          </div>
        </div>

        {/* In-Drawer Quick Search */}
        <div className="drawer-search-wrap">
          <form onSubmit={handleSearch} className="drawer-search-form" action="/search" method="GET">
            <input
              id="drawer-search-input"
              name="q"
              type="text"
              placeholder="Search exams, results, notifications..."
              value={drawerSearch}
              onChange={(e) => setDrawerSearch(e.target.value)}
              required
              aria-label="Search notifications"
            />
            <button type="submit" aria-label="Submit search">
              <Search size={16} />
            </button>
          </form>
        </div>

        <div className="drawer-body-content">
          {/* Primary Hubs (2x2 Big Touch Cards) */}
          <div className="drawer-section-title">⚡ PRIMARY HUBS</div>
          <div className="drawer-hubs-grid">
            <Link href="/results" className="drawer-hub-card" onClick={closeDrawer}>
              <div className="hub-icon">📋</div>
              <div className="hub-label">Results</div>
              <div className="hub-sub">Merit lists & cutoffs</div>
            </Link>
            <Link href="/admit-card" className="drawer-hub-card" onClick={closeDrawer}>
              <div className="hub-icon">🎫</div>
              <div className="hub-label">Admit Card</div>
              <div className="hub-sub">Hall tickets & slips</div>
            </Link>
            <Link href="/recruitment" className="drawer-hub-card" onClick={closeDrawer}>
              <div className="hub-icon">💼</div>
              <div className="hub-label">Recruitment</div>
              <div className="hub-sub">10,000+ Govt vacancies</div>
            </Link>
            <Link href="/exam" className="drawer-hub-card" onClick={closeDrawer}>
              <div className="hub-icon">📝</div>
              <div className="hub-label">Exam Dates</div>
              <div className="hub-sub">Schedules & calendars</div>
            </Link>
          </div>

          {/* State Matrix in Drawer */}
          <div className="drawer-section-title">🗺️ STATE-WISE RECRUITMENT</div>
          <div className="drawer-chip-cluster">
            <Link href="/state/central-govt" className="chip-item" onClick={closeDrawer}>🏛️ Central Govt</Link>
            <Link href="/state/west-bengal" className="chip-item" onClick={closeDrawer}>🌊 West Bengal</Link>
            <Link href="/state/uttar-pradesh" className="chip-item" onClick={closeDrawer}>🌾 Uttar Pradesh</Link>
            <Link href="/state/bihar" className="chip-item" onClick={closeDrawer}>🚩 Bihar</Link>
            <Link href="/state/rajasthan" className="chip-item" onClick={closeDrawer}>🏰 Rajasthan</Link>
            <Link href="/state/madhya-pradesh" className="chip-item" onClick={closeDrawer}>🌲 Madhya Pradesh</Link>
            <Link href="/state/maharashtra" className="chip-item" onClick={closeDrawer}>🏙️ Maharashtra</Link>
            <Link href="/state/all-india" className="chip-item" onClick={closeDrawer}>🇮🇳 All India</Link>
          </div>

          {/* Recruitment & Opportunities */}
          <div className="drawer-section-title">🏛️ RECRUITMENT & OPPORTUNITIES</div>
          <div className="drawer-chip-cluster">
            <Link href="/recruitment" className="chip-item" onClick={closeDrawer}>💼 All Recruitment</Link>
            <Link href="/category/government-jobs" className="chip-item" onClick={closeDrawer}>🏛️ Government Jobs</Link>
            <Link href="/category/application-form" className="chip-item" onClick={closeDrawer}>📑 Application Forms</Link>
            <Link href="/category/scholarship" className="chip-item" onClick={closeDrawer}>🏆 Scholarships</Link>
          </div>

          {/* Exams & Admissions */}
          <div className="drawer-section-title">🎯 EXAMS & ADMISSIONS</div>
          <div className="drawer-chip-cluster">
            <Link href="/exam" className="chip-item" onClick={closeDrawer}>📝 Exam Calendar</Link>
            <Link href="/answer-key" className="chip-item" onClick={closeDrawer}>🔑 Answer Keys</Link>
            <Link href="/category/entrance-exams" className="chip-item" onClick={closeDrawer}>🎯 JEE / NEET / CUET</Link>
            <Link href="/category/board-exams" className="chip-item" onClick={closeDrawer}>🏫 CBSE & ICSE Boards</Link>
            <Link href="/category/admission" className="chip-item" onClick={closeDrawer}>🎓 Admission & Counseling</Link>
          </div>

          {/* Policies & Legal */}
          <div className="drawer-section-title">🛡️ ABOUT & POLICIES</div>
          <div className="drawer-chip-cluster">
            <Link href="/about" className="chip-item" onClick={closeDrawer}>ℹ️ About Us</Link>
            <Link href="/disclaimer" className="chip-item" onClick={closeDrawer}>⚖️ Disclaimer</Link>
            <Link href="/privacy-policy" className="chip-item" onClick={closeDrawer}>🔒 Privacy Policy</Link>
            <Link href="/terms-and-conditions" className="chip-item" onClick={closeDrawer}>📜 Terms & Conditions</Link>
            <Link href="/contact" className="chip-item" onClick={closeDrawer}>📬 Contact</Link>
            <Link href="/rss.xml" className="chip-item" onClick={closeDrawer}>📡 RSS Feed</Link>
          </div>
        </div>
      </div>
    </>
  );
}
