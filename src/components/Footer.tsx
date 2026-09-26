import React from 'react';
import Link from 'next/link';

export function Footer() {
  const currentYear = new Date().getFullYear();

  return (
    <footer className="site-footer">
      <div className="site-container footer-content">
        <div className="footer-col brand-col">
          <div className="brand-logo-wrap" style={{ marginBottom: '0.75rem' }}>
            <div className="brand-text-stack">
              <div className="brand-title">
                <span className="logo-accent" style={{ color: '#60a5fa' }}>EduGov</span>
                <span className="logo-sub" style={{ color: '#ffffff' }}>News<span className="logo-dot">.</span></span>
              </div>
            </div>
          </div>
          <p className="footer-desc">
            EduGov News is a high-speed education news dissemination platform providing direct access to verified public government announcements, recruitment notices, admit cards, and examination schedules.
          </p>
          <div className="footer-disclaimer-badge">
            ⚖️ <strong>Disclaimer:</strong> EduGov News is an independent news reporting portal and is NOT affiliated with any government authority. Always verify details on official government (.gov.in/.nic.in) portals.
          </div>
        </div>

        <div className="footer-col">
          <div className="footer-heading">⚡ Primary Hubs</div>
          <ul className="footer-links">
            <li><Link href="/results">Results & Merit Lists</Link></li>
            <li><Link href="/admit-card">Admit Cards & Slips</Link></li>
            <li><Link href="/recruitment">Government Recruitment</Link></li>
            <li><Link href="/exam">Exam Schedules & Keys</Link></li>
            <li><Link href="/answer-key">Official Answer Keys</Link></li>
            <li><Link href="/category/scholarship">National Scholarships</Link></li>
          </ul>
        </div>

        <div className="footer-col">
          <div className="footer-heading">🗺️ State Portals</div>
          <ul className="footer-links">
            <li><Link href="/state/central-govt">Central Govt Jobs</Link></li>
            <li><Link href="/state/west-bengal">West Bengal (WBPSC)</Link></li>
            <li><Link href="/state/uttar-pradesh">Uttar Pradesh (UPPSC)</Link></li>
            <li><Link href="/state/bihar">Bihar (BPSC)</Link></li>
            <li><Link href="/state/rajasthan">Rajasthan (RPSC)</Link></li>
            <li><Link href="/state/madhya-pradesh">Madhya Pradesh (MPPSC)</Link></li>
            <li><Link href="/state/maharashtra">Maharashtra (MPSC)</Link></li>
            <li><Link href="/state/all-india">All India Portals</Link></li>
          </ul>
        </div>

        <div className="footer-col">
          <div className="footer-heading">🛡️ Legal & Compliance</div>
          <ul className="footer-links">
            <li><Link href="/about">About Us</Link></li>
            <li><Link href="/contact">Contact Support</Link></li>
            <li><Link href="/privacy-policy">Privacy Policy</Link></li>
            <li><Link href="/terms-and-conditions">Terms & Conditions</Link></li>
            <li><Link href="/disclaimer">Official Disclaimer</Link></li>
            <li><Link href="/copyright-policy">Copyright Policy</Link></li>
          </ul>
        </div>
      </div>

      <div className="site-container footer-bottom">
        <p>© {currentYear} EduGov News Portal. All rights reserved. Ingested from verified official education & public recruitment portals.</p>
        <p style={{ fontSize: '0.6875rem', color: '#64748b' }}>Powered by Next.js 15 App Router & MongoDB Atlas Cloud Engine.</p>
      </div>
    </footer>
  );
}
