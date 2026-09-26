import React from 'react';
import type { Metadata } from 'next';
import Link from 'next/link';

export const metadata: Metadata = {
  title: 'Official Disclaimer — EduGov News',
  description: 'Legal disclaimer and non-affiliation notice for EduGov News portal.',
};

export default function DisclaimerPage() {
  return (
    <div className="article-container" style={{ maxWidth: '850px', margin: '0 auto' }}>
      <nav className="breadcrumb-nav">
        <Link href="/">Home</Link>
        <span className="separator">/</span>
        <span>Disclaimer</span>
      </nav>

      <h1 className="article-title-h1" style={{ marginBottom: '1.25rem' }}>
        Official Disclaimer & Non-Affiliation Notice
      </h1>

      <div className="article-main-body" style={{ fontSize: '0.95rem', lineHeight: '1.7' }}>
        <div className="source-verification-box" style={{ marginBottom: '1.5rem' }}>
          <div className="source-verification-header">
            <span>⚖️</span>
            <span className="source-verif-heading">Non-Affiliation Notice</span>
          </div>
          <p className="source-verif-desc">
            EduGov News is an independent educational news reporting portal and is <strong>NOT associated, affiliated, endorsed, or connected in any manner with any government authority or exam conducting agency</strong>.
          </p>
        </div>

        <p>
          All information published on this website is compiled from publicly accessible official notifications, press releases, and websites of respective government recruitment bodies (e.g. UPSC, SSC, IBPS, NTA, State Public Service Commissions).
        </p>

        <p>
          While we make every effort to verify all dates, requirements, and links, candidates must always verify details directly on the respective official government portal before taking any action.
        </p>
      </div>
    </div>
  );
}
