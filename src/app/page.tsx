import React from 'react';
import Link from 'next/link';
import { HeroNoticesBoard } from '@/components/HeroNoticesBoard';
import { Sidebar } from '@/components/Sidebar';
import { getTop10Notices, getArticlesByCategory, getSidebarNotices } from '@/lib/db';
import { Article } from '@/types';

export const revalidate = 60; // ISR revalidate every 60 seconds

export default async function HomePage() {
  const [top10Notices, resultsData, admitData, recruitmentData, examData, sidebarNotices] =
    await Promise.all([
      getTop10Notices(),
      getArticlesByCategory('results', 1, 6),
      getArticlesByCategory('admit-card', 1, 6),
      getArticlesByCategory('recruitment', 1, 6),
      getArticlesByCategory('exam', 1, 6),
      getSidebarNotices(10),
    ]);

  return (
    <>
      {/* Top 10 Latest Notices Text Hero Board */}
      <HeroNoticesBoard notices={top10Notices} />

      {/* Instant WhatsApp & Telegram Alert Banner */}
      <div className="social-alert-banner">
        <div className="social-alert-text">
          <div className="social-alert-heading">
            <span>🔔 Never Miss an Exam or Job Notice!</span>
          </div>
          <p className="social-alert-sub">
            Join 100,000+ students receiving instant verified official notifications directly on phone.
          </p>
        </div>
        <div className="social-alert-buttons">
          <a
            href="https://telegram.me"
            target="_blank"
            rel="noopener noreferrer"
            className="link-btn social-tg-btn"
          >
            ✈️ Join Telegram
          </a>
          <a
            href="https://whatsapp.com"
            target="_blank"
            rel="noopener noreferrer"
            className="link-btn social-wa-btn"
          >
            💬 Join WhatsApp
          </a>
        </div>
      </div>

      {/* State-Wise Quick Filter Matrix */}
      <section id="state-matrix" className="state-filter-section">
        <div className="section-header-bar">
          <h2 className="section-bar-title">
            🗺️ State & Central Government Jobs Filter
          </h2>
        </div>
        <div className="state-matrix-grid">
          <Link href="/state/central-govt" className="state-card-btn">🏛️ Central Govt</Link>
          <Link href="/state/west-bengal" className="state-card-btn">🌊 West Bengal</Link>
          <Link href="/state/uttar-pradesh" className="state-card-btn">🌾 Uttar Pradesh</Link>
          <Link href="/state/bihar" className="state-card-btn">🚩 Bihar</Link>
          <Link href="/state/rajasthan" className="state-card-btn">🏰 Rajasthan</Link>
          <Link href="/state/madhya-pradesh" className="state-card-btn">🌲 Madhya Pradesh</Link>
          <Link href="/state/maharashtra" className="state-card-btn">🏙️ Maharashtra</Link>
          <Link href="/state/all-india" className="state-card-btn">🇮🇳 All India</Link>
        </div>
      </section>

      {/* Homepage Main Feed & Sidebar Grid */}
      <div className="feed-layout-grid">
        {/* Main Categorized Columns */}
        <main className="feed-main-col">
          {/* 1. Results Block */}
          {resultsData.articles.length > 0 && (
            <section className="category-block-card">
              <div className="block-header block-header-blue">
                <h2 className="block-title">
                  <span>📋</span> Latest Results & Merit Lists
                </h2>
                <Link href="/results" className="view-all-link">View All »</Link>
              </div>
              <div className="category-items-grid">
                {resultsData.articles.map((art: Article) => (
                  <article key={art.slug} className="compact-card">
                    <div>
                      <span className="official-verified-badge">
                        ✓ {art.official_source_name || 'Official'}
                      </span>
                      <h3 className="compact-card-title">
                        <Link href={`/news/${art.slug}`}>{art.title}</Link>
                      </h3>
                    </div>
                    <div className="compact-card-footer">
                      <span>📅 {new Date(art.published_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                      <Link href={`/news/${art.slug}`} className="card-action-link">Check Result »</Link>
                    </div>
                  </article>
                ))}
              </div>
            </section>
          )}

          {/* 2. Admit Card Block */}
          {admitData.articles.length > 0 && (
            <section className="category-block-card">
              <div className="block-header block-header-cyan">
                <h2 className="block-title">
                  <span>🎫</span> Admit Cards & Hall Tickets
                </h2>
                <Link href="/admit-card" className="view-all-link">View All »</Link>
              </div>
              <div className="category-items-grid">
                {admitData.articles.map((art: Article) => (
                  <article key={art.slug} className="compact-card">
                    <div>
                      <span className="official-verified-badge">
                        ✓ {art.official_source_name || 'Official'}
                      </span>
                      <h3 className="compact-card-title">
                        <Link href={`/news/${art.slug}`}>{art.title}</Link>
                      </h3>
                    </div>
                    <div className="compact-card-footer">
                      <span>📅 {new Date(art.published_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                      <Link href={`/news/${art.slug}`} className="card-action-link">Download Slip »</Link>
                    </div>
                  </article>
                ))}
              </div>
            </section>
          )}

          {/* 3. Recruitment Block */}
          {recruitmentData.articles.length > 0 && (
            <section className="category-block-card">
              <div className="block-header block-header-green">
                <h2 className="block-title">
                  <span>💼</span> Government & Banking Recruitment
                </h2>
                <Link href="/recruitment" className="view-all-link">View All »</Link>
              </div>
              <div className="category-items-grid">
                {recruitmentData.articles.map((art: Article) => (
                  <article key={art.slug} className="compact-card">
                    <div>
                      <span className="official-verified-badge">
                        ✓ {art.official_source_name || 'Official'}
                      </span>
                      <h3 className="compact-card-title">
                        <Link href={`/news/${art.slug}`}>{art.title}</Link>
                      </h3>
                    </div>
                    <div className="compact-card-footer">
                      <span>📅 {new Date(art.published_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                      <Link href={`/news/${art.slug}`} className="card-action-link">Apply Online »</Link>
                    </div>
                  </article>
                ))}
              </div>
            </section>
          )}

          {/* 4. Exam Dates Block */}
          {examData.articles.length > 0 && (
            <section className="category-block-card">
              <div className="block-header block-header-amber">
                <h2 className="block-title">
                  <span>📝</span> Exam Schedules & Answer Keys
                </h2>
                <Link href="/exam" className="view-all-link">View All »</Link>
              </div>
              <div className="category-items-grid">
                {examData.articles.map((art: Article) => (
                  <article key={art.slug} className="compact-card">
                    <div>
                      <span className="official-verified-badge">
                        ✓ {art.official_source_name || 'Official'}
                      </span>
                      <h3 className="compact-card-title">
                        <Link href={`/news/${art.slug}`}>{art.title}</Link>
                      </h3>
                    </div>
                    <div className="compact-card-footer">
                      <span>📅 {new Date(art.published_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                      <Link href={`/news/${art.slug}`} className="card-action-link">View Dates »</Link>
                    </div>
                  </article>
                ))}
              </div>
            </section>
          )}
        </main>

        {/* Standard Reusable Right Sidebar */}
        <Sidebar latestNotices={sidebarNotices} />
      </div>
    </>
  );
}
