import React from 'react';
import type { Metadata } from 'next';
import Link from 'next/link';

export const metadata: Metadata = {
  title: 'Contact Support — EduGov News',
  description: 'Get in touch with the EduGov News team for queries, corrections, or editorial feedback.',
};

export default function ContactPage() {
  return (
    <div className="article-container" style={{ maxWidth: '850px', margin: '0 auto' }}>
      <nav className="breadcrumb-nav">
        <Link href="/">Home</Link>
        <span className="separator">/</span>
        <span>Contact Us</span>
      </nav>

      <h1 className="article-title-h1" style={{ marginBottom: '1.25rem' }}>
        Contact EduGov News
      </h1>

      <div className="article-main-body" style={{ fontSize: '0.95rem', lineHeight: '1.7' }}>
        <p>
          Have questions, feedback, or notice corrections? Please feel free to reach out to our editorial and technical team.
        </p>

        <div style={{ background: 'var(--color-bg)', border: '1px solid var(--color-border)', borderRadius: '8px', padding: '1.25rem', marginTop: '1.5rem' }}>
          <h3 style={{ marginTop: 0 }}>Editorial & Grievance Desk</h3>
          <p>📧 Email: <a href="mailto:support@edugovnews.com" style={{ color: '#2563eb', fontWeight: 600 }}>support@edugovnews.com</a></p>
          <p>⏰ Response Time: Typically within 24–48 business hours.</p>
        </div>
      </div>
    </div>
  );
}
