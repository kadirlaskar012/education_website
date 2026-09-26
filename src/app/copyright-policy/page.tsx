import React from 'react';
import type { Metadata } from 'next';
import Link from 'next/link';

export const metadata: Metadata = {
  title: 'Copyright Policy — EduGov News',
  description: 'Copyright and fair use policy for EduGov News.',
};

export default function CopyrightPage() {
  return (
    <div className="article-container" style={{ maxWidth: '850px', margin: '0 auto' }}>
      <nav className="breadcrumb-nav">
        <Link href="/">Home</Link>
        <span className="separator">/</span>
        <span>Copyright Policy</span>
      </nav>

      <h1 className="article-title-h1" style={{ marginBottom: '1.25rem' }}>
        Copyright & Fair Use Policy
      </h1>

      <div className="article-main-body" style={{ fontSize: '0.95rem', lineHeight: '1.7' }}>
        <p>
          EduGov News respects intellectual property rights and adheres strictly to fair use guidelines for educational reporting and public information dissemination.
        </p>
        <p>
          Government circulars, gazette notifications, and official press releases cited on this portal are public domain documents referenced solely for news reporting and informative guidance.
        </p>
      </div>
    </div>
  );
}
