import React from 'react';
import type { Metadata } from 'next';
import Link from 'next/link';

export const metadata: Metadata = {
  title: 'About Us — EduGov News',
  description: 'Learn about EduGov News, our mission to provide verified government job and education notices.',
};

export default function AboutPage() {
  return (
    <div className="article-container" style={{ maxWidth: '850px', margin: '0 auto' }}>
      <nav className="breadcrumb-nav">
        <Link href="/">Home</Link>
        <span className="separator">/</span>
        <span>About Us</span>
      </nav>

      <h1 className="article-title-h1" style={{ marginBottom: '1.25rem' }}>
        About EduGov News Portal
      </h1>

      <div className="article-main-body" style={{ fontSize: '0.95rem', lineHeight: '1.7' }}>
        <p>
          <strong>EduGov News</strong> is a dedicated, real-time education and public recruitment news reporting platform designed to deliver verified examination dates, admit card download notices, recruitment notifications, and merit list results to students and job aspirants across India.
        </p>

        <h3>Our Objective</h3>
        <p>
          With hundreds of state and central recruitment boards releasing updates daily, students often face delays or misinformation. Our platform bridges this gap by automatically monitoring official government portals (such as UPSC, SSC, RRB, NTA, and State PSCs) and delivering structured, authentic summaries along with direct links to official documents.
        </p>

        <h3>Editorial & Verification Standards</h3>
        <p>
          Every update published on EduGov News references official source URLs (`.gov.in` / `.nic.in` domains) and official PDF notices. We strictly adhere to factual reporting and non-affiliation standards.
        </p>
      </div>
    </div>
  );
}
