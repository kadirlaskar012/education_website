'use client';

import React from 'react';
import { useTheme } from './ThemeProvider';
import { Sun, Moon } from 'lucide-react';

interface ThemeToggleProps {
  showLabel?: boolean;
  className?: string;
  style?: React.CSSProperties;
}

export function ThemeToggle({ showLabel = true, className = '', style }: ThemeToggleProps) {
  const { theme, toggleTheme } = useTheme();

  return (
    <button
      type="button"
      onClick={toggleTheme}
      className={`theme-toggle-btn js-theme-toggle ${className}`}
      aria-label="Toggle Dark/Light Mode"
      style={style}
    >
      <span className="theme-icon-slot">
        {theme === 'dark' ? (
          <Moon size={16} className="text-sky-400" />
        ) : (
          <Sun size={16} className="text-amber-500" />
        )}
      </span>
      {showLabel && (
        <span className="theme-label">
          {theme === 'dark' ? 'Light Mode' : 'Eye Comfort'}
        </span>
      )}
    </button>
  );
}
