'use client';

import React, { useState } from 'react';
import { Source } from '@/types';

interface SourcesClientProps {
  sources: Source[];
}

export function SourcesClient({ sources }: SourcesClientProps) {
  const [isRunning, setIsRunning] = useState(false);
  const [resultMsg, setResultMsg] = useState<string | null>(null);

  const runScraperBatch = async () => {
    setIsRunning(true);
    setResultMsg(null);

    try {
      const res = await fetch('/api/cron/scrape');
      const data = await res.json();
      if (data.success) {
        setResultMsg(`✓ Batch Complete! Scraped ${data.batchDetails?.length || 0} portals, found ${data.totalNewNotices || 0} new notices.`);
      } else {
        setResultMsg(`Scrape ended: ${data.message || data.error || 'Done'}`);
      }
    } catch (err) {
      setResultMsg(`Error running scraper: ${(err as Error).message}`);
    } finally {
      setIsRunning(false);
    }
  };

  return (
    <div>
      <div className="admin-header-row">
        <div>
          <h1 style={{ fontSize: '1.4rem', fontWeight: 800, color: 'var(--color-text-main)', margin: 0 }}>
            Official Scraper Portals Management
          </h1>
          <p style={{ fontSize: '0.8125rem', color: 'var(--color-text-muted)', marginTop: '0.25rem' }}>
            26+ National & State Government Portals Monitored with automated deduplication
          </p>
        </div>
        <div>
          <button
            type="button"
            disabled={isRunning}
            onClick={runScraperBatch}
            className="admin-btn admin-btn-primary"
            style={{ display: 'inline-flex', alignItems: 'center', gap: '0.4rem', cursor: isRunning ? 'not-allowed' : 'pointer' }}
          >
            {isRunning ? '⏳ Running Scraper Batch...' : '⚡ Trigger Live Scrape Now'}
          </button>
        </div>
      </div>

      {resultMsg && (
        <div
          style={{
            background: '#ecfdf5',
            border: '1px solid #a7f3d0',
            color: '#065f46',
            padding: '0.75rem 1rem',
            borderRadius: '6px',
            marginBottom: '1.5rem',
            fontSize: '0.875rem',
            fontWeight: 600,
          }}
        >
          {resultMsg}
        </div>
      )}

      {/* Sources Table */}
      <div className="admin-card">
        <div style={{ overflowX: 'auto' }}>
          <table className="admin-table">
            <thead>
              <tr>
                <th>Authority / Portal Name</th>
                <th>State / Region</th>
                <th>Type</th>
                <th>Status</th>
                <th>Feed / Portal URL</th>
                <th>Last Scraped</th>
              </tr>
            </thead>
            <tbody>
              {sources.map((src) => (
                <tr key={src.slug}>
                  <td style={{ fontWeight: 700 }}>{src.name}</td>
                  <td>
                    <span className="cat-badge-micro">{src.state}</span>
                  </td>
                  <td>{src.scraper_type}</td>
                  <td>
                    <span className={`status-badge ${src.is_active ? 'status-published' : 'status-draft'}`}>
                      {src.is_active ? 'ACTIVE' : 'PAUSED'}
                    </span>
                  </td>
                  <td>
                    {src.feed_url ? (
                      <a
                        href={src.feed_url}
                        target="_blank"
                        rel="noopener noreferrer"
                        style={{ color: '#2563eb', fontSize: '0.75rem' }}
                      >
                        {src.feed_url.length > 45 ? `${src.feed_url.slice(0, 45)}...` : src.feed_url}
                      </a>
                    ) : (
                      <span style={{ color: 'var(--text-muted)', fontSize: '0.75rem' }}>—</span>
                    )}
                  </td>
                  <td style={{ fontSize: '0.75rem' }}>
                    {src.last_scraped_at
                      ? new Date(src.last_scraped_at).toLocaleString('en-US', {
                          month: 'short',
                          day: 'numeric',
                          hour: 'numeric',
                          minute: '2-digit',
                        })
                      : 'Never'}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
