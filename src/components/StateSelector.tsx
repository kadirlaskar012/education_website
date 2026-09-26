'use client';

import React, { useState } from 'react';
import Link from 'next/link';

interface StateOption {
  slug: string;
  name: string;
}

const ALL_STATES: StateOption[] = [
  { slug: 'all-india', name: '🇮🇳 All India / National' },
  { slug: 'central-govt', name: '🏛️ Central Government' },
  { slug: 'west-bengal', name: '🌊 West Bengal (WBPSC)' },
  { slug: 'uttar-pradesh', name: '🌾 Uttar Pradesh (UPPSC)' },
  { slug: 'bihar', name: '🚩 Bihar (BPSC)' },
  { slug: 'rajasthan', name: '🏰 Rajasthan (RPSC)' },
  { slug: 'madhya-pradesh', name: '🌲 Madhya Pradesh (MPPSC)' },
  { slug: 'maharashtra', name: '🏙️ Maharashtra (MPSC)' },
];

interface StateSelectorProps {
  categorySlug: string;
  selectedState?: string;
  totalCount: number;
  basePath?: string;
}

export function StateSelector({ categorySlug, selectedState, totalCount, basePath }: StateSelectorProps) {
  const [isOpen, setIsOpen] = useState(false);

  const rootPath = basePath || `/category/${categorySlug}`;
  const currentStateObj = ALL_STATES.find((s) => s.slug === selectedState);

  return (
    <div className="state-filter-box">
      <div className="state-selector-bar">
        {/* Toggle Dropdown Button */}
        <button
          type="button"
          className="btn-state-dropdown-trigger"
          onClick={() => setIsOpen(!isOpen)}
          aria-expanded={isOpen}
        >
          <span>🗺️ Select State / Region</span>
          <span className="caret-icon">{isOpen ? '▴' : '▾'}</span>
        </button>

        {/* Selected State Label & Clear Option */}
        {selectedState && selectedState !== 'all-india' && currentStateObj ? (
          <div className="selected-state-active-badge">
            <span className="active-state-name">
              📍 <strong>{currentStateObj.name}</strong>
            </span>
            <Link
              href={rootPath}
              className="btn-clear-state-filter"
              title="Clear State Filter"
            >
              ✕ Clear
            </Link>
          </div>
        ) : (
          <div className="selected-state-default-badge">
            <span>Showing: All Regions ({totalCount} Updates)</span>
          </div>
        )}
      </div>

      {/* Collapsible State Grid */}
      {isOpen && (
        <div className="state-options-collapse" style={{ display: 'block' }}>
          <div className="state-options-header">
            <span className="state-options-title">Filter Notices by State / Central Board:</span>
            <button
              type="button"
              className="btn-close-state-panel"
              onClick={() => setIsOpen(false)}
            >
              ✕ Close
            </button>
          </div>
          <div className="state-options-grid">
            <Link
              href={rootPath}
              onClick={() => setIsOpen(false)}
              className={`state-option-item ${!selectedState || selectedState === 'all-india' ? 'active' : ''}`}
            >
              🇮🇳 All Regions (Default)
            </Link>
            {ALL_STATES.filter((s) => s.slug !== 'all-india').map((st) => (
              <Link
                key={st.slug}
                href={`${rootPath}?state=${st.slug}`}
                onClick={() => setIsOpen(false)}
                className={`state-option-item ${selectedState === st.slug ? 'active' : ''}`}
              >
                {st.name}
              </Link>
            ))}
          </div>
        </div>
      )}
    </div>
  );
}
