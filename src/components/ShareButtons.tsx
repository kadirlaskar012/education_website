'use client';

import React, { useState } from 'react';

interface ShareButtonsProps {
  title: string;
  slug: string;
}

export function ShareButtons({ title, slug }: ShareButtonsProps) {
  const [copied, setCopied] = useState(false);

  const url = typeof window !== 'undefined' ? `${window.location.origin}/news/${slug}` : `/news/${slug}`;

  const handleCopy = () => {
    if (navigator.clipboard) {
      navigator.clipboard.writeText(url).then(() => {
        setCopied(true);
        setTimeout(() => setCopied(false), 2000);
      });
    }
  };

  const handlePrint = () => {
    if (typeof window !== 'undefined') {
      window.print();
    }
  };

  return (
    <div className="share-bar-row">
      <span className="share-label">Share:</span>
      <div className="share-buttons-group">
        <a
          href={`https://api.whatsapp.com/send?text=${encodeURIComponent(`${title} - ${url}`)}`}
          target="_blank"
          rel="noopener noreferrer"
          className="link-btn share-btn-wa"
        >
          WhatsApp
        </a>
        <a
          href={`https://t.me/share/url?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`}
          target="_blank"
          rel="noopener noreferrer"
          className="link-btn share-btn-tg"
        >
          Telegram
        </a>
        <button
          type="button"
          onClick={handleCopy}
          className="link-btn share-btn-action js-copy-link"
        >
          {copied ? '✓ Copied!' : '📋 Copy Link'}
        </button>
        <button
          type="button"
          onClick={handlePrint}
          className="link-btn share-btn-action"
        >
          🖨️ Print
        </button>
      </div>
    </div>
  );
}
