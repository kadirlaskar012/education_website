import React from 'react';
import type { Metadata } from 'next';
import Link from 'next/link';

export const metadata: Metadata = {
  title: 'Privacy Policy — EduGov News',
  description: 'Privacy Policy and data protection guidelines for users of EduGov News portal.',
};

export default function PrivacyPolicyPage() {
  return (
    <div className="article-container" style={{ maxWidth: '850px', margin: '0 auto' }}>
      <nav className="breadcrumb-nav">
        <Link href="/">Home</Link>
        <span className="separator">/</span>
        <span>Privacy Policy</span>
      </nav>

      <h1 className="article-title-h1" style={{ marginBottom: '1.25rem' }}>
        Privacy Policy
      </h1>

      <div className="article-main-body" style={{ fontSize: '0.95rem', lineHeight: '1.7' }}>
        <p>Last updated: {new Date().getFullYear()}</p>
        <p>
          At EduGov News, accessible from our website, one of our main priorities is the privacy of our visitors. This Privacy Policy document outlines the types of information collected and how we use it.
        </p>

        <h3>Log Files</h3>
        <p>
          EduGov News follows a standard procedure of using log files. These files log visitors when they visit websites. The information collected includes internet protocol (IP) addresses, browser type, Internet Service Provider (ISP), date/time stamp, referring/exit pages, and number of clicks.
        </p>

        <h3>Cookies and Web Beacons</h3>
        <p>
          Like any other website, EduGov News uses 'cookies' to store preferences and deliver a fast, responsive browsing experience.
        </p>
      </div>
    </div>
  );
}
