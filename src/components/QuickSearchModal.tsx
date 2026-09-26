'use client';

import React, { useState, useEffect, useRef } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import { useUI } from './UIContext';
import { Search, X } from 'lucide-react';

const POPULAR_SEARCHES = ['SSC', 'UPSC', 'Railway', 'Admit Card', 'Results', 'Rajasthan', 'West Bengal', 'NEET'];

export function QuickSearchModal() {
  const { isSearchModalOpen, closeSearchModal } = useUI();
  const [query, setQuery] = useState('');
  const inputRef = useRef<HTMLInputElement>(null);
  const router = useRouter();

  useEffect(() => {
    if (isSearchModalOpen) {
      setTimeout(() => {
        inputRef.current?.focus();
      }, 100);
    } else {
      setQuery('');
    }
  }, [isSearchModalOpen]);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (query.trim()) {
      closeSearchModal();
      router.push(`/search?q=${encodeURIComponent(query.trim())}`);
    }
  };

  if (!isSearchModalOpen) return null;

  return (
    <>
      <div className="search-modal-backdrop active" onClick={closeSearchModal} />
      <div
        className="quick-search-modal open"
        role="dialog"
        aria-modal="true"
        aria-label="Quick Search"
      >
        <div className="search-modal-header">
          <div className="search-modal-title">
            <Search size={18} />
            <span>Search Official Notices</span>
          </div>
          <button
            type="button"
            className="btn-close-search-modal"
            onClick={closeSearchModal}
            aria-label="Close search modal"
          >
            <X size={16} />
          </button>
        </div>

        <div className="search-modal-body">
          <form onSubmit={handleSubmit} className="search-modal-form">
            <div className="search-modal-input-wrap">
              <input
                ref={inputRef}
                id="modal-search-input"
                name="q"
                type="text"
                value={query}
                onChange={(e) => setQuery(e.target.value)}
                placeholder="Search exams, results, admit cards, notices..."
                required
                autoComplete="off"
              />
              <button type="submit" className="btn-search-modal-submit">
                Search
              </button>
            </div>
          </form>

          <div className="search-modal-quick-tags">
            <span className="quick-tag-label">Popular Searches:</span>
            <div className="quick-tag-pills">
              {POPULAR_SEARCHES.map((tag) => (
                <Link
                  key={tag}
                  href={`/search?q=${encodeURIComponent(tag)}`}
                  onClick={closeSearchModal}
                  className="search-tag-pill"
                >
                  {tag}
                </Link>
              ))}
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
