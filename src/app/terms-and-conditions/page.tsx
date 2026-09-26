import React from 'react';
import type { Metadata } from 'next';
import Link from 'next/link';

export const metadata: Metadata = {
  title: 'Terms and Conditions — EduGov News',
  description: 'Terms and conditions of use for EduGov News education news portal.',
};

export default function TermsPage() {
  return (
    <div className="article-container" style={{ maxWidth: '850px', margin: '0 auto' }}>
      <nav className="breadcrumb-nav">
        <Link href="/">Home</Link>
        <span className="separator">/</span>
        <span>Terms & Conditions</span>
      </nav>

      <h1 className="article-title-h1" style={{ marginBottom: '1.25rem' }}>
        Terms and Conditions
      </h1>

      <div className="article-main-body" style={{ fontSize: '0.95rem', lineHeight: '1.7' }}>
        <p>
          By accessing and using EduGov News, you agree to comply with and be bound by the following terms and conditions of use.
        </p>
        <h3>Use of Content</h3>
        <p>
          Content on this site is provided for informational and educational purposes only. Users are advised to cross-check all deadlines and application criteria with the official notifications linked within each article.
        </p>
      </div>
    </div>
  );
}
