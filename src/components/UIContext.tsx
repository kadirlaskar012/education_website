'use client';

import React, { createContext, useContext, useState, useEffect } from 'react';

interface UIContextType {
  isDrawerOpen: boolean;
  isSearchModalOpen: boolean;
  openDrawer: () => void;
  closeDrawer: () => void;
  openSearchModal: () => void;
  closeSearchModal: () => void;
}

const UIContext = createContext<UIContextType>({
  isDrawerOpen: false,
  isSearchModalOpen: false,
  openDrawer: () => {},
  closeDrawer: () => {},
  openSearchModal: () => {},
  closeSearchModal: () => {},
});

export function UIProvider({ children }: { children: React.ReactNode }) {
  const [isDrawerOpen, setIsDrawerOpen] = useState(false);
  const [isSearchModalOpen, setIsSearchModalOpen] = useState(false);

  const openDrawer = () => {
    setIsSearchModalOpen(false);
    setIsDrawerOpen(true);
    document.body.classList.add('drawer-locked');
  };

  const closeDrawer = () => {
    setIsDrawerOpen(false);
    document.body.classList.remove('drawer-locked');
  };

  const openSearchModal = () => {
    setIsDrawerOpen(false);
    setIsSearchModalOpen(true);
    document.body.classList.add('modal-locked');
  };

  const closeSearchModal = () => {
    setIsSearchModalOpen(false);
    document.body.classList.remove('modal-locked');
  };

  // Keyboard Escape listener
  useEffect(() => {
    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === 'Escape') {
        closeDrawer();
        closeSearchModal();
      }
    };
    window.addEventListener('keydown', handleKeyDown);
    return () => window.removeEventListener('keydown', handleKeyDown);
  }, []);

  return (
    <UIContext.Provider
      value={{
        isDrawerOpen,
        isSearchModalOpen,
        openDrawer,
        closeDrawer,
        openSearchModal,
        closeSearchModal,
      }}
    >
      {children}
    </UIContext.Provider>
  );
}

export function useUI() {
  return useContext(UIContext);
}
