import type { Metadata } from 'next';
import './globals.css';
import { ThemeProvider } from '@/components/ThemeProvider';
import { UIProvider } from '@/components/UIContext';
import { Header } from '@/components/Header';
import { MobileTabs } from '@/components/MobileTabs';
import { BreakingTicker } from '@/components/BreakingTicker';
import { MobileDrawer } from '@/components/MobileDrawer';
import { QuickSearchModal } from '@/components/QuickSearchModal';
import { MobileBottomNav } from '@/components/MobileBottomNav';
import { Footer } from '@/components/Footer';
import { getBreakingNews } from '@/lib/db';

export const metadata: Metadata = {
  title: {
    template: '%s | EduGov News',
    default: 'EduGov News — Official Education & Recruitment Portal',
  },
  description:
    'Instant & verified official educational notifications, exam dates, admit cards, results, and government job vacancy alerts directly from official public portals.',
  metadataBase: new URL(process.env.NEXT_PUBLIC_SITE_URL || 'http://localhost:3000'),
  alternates: {
    canonical: '/',
    types: {
      'application/rss+xml': [{ url: '/rss.xml', title: 'EduGov News RSS Feed' }],
    },
  },
  openGraph: {
    siteName: 'EduGov News',
    type: 'website',
    locale: 'en_IN',
  },
};

export default async function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  const breakingNews = await getBreakingNews(10);

  return (
    <html lang="en" data-theme="light" suppressHydrationWarning>
      <head>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="anonymous" />
        <link
          href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap"
          rel="stylesheet"
        />
      </head>
      <body className="site-body">
        <ThemeProvider>
          <UIProvider>
            {/* Header with desktop dropdown & trust bar */}
            <Header />

            {/* Mobile Swipeable Tab Ribbon */}
            <MobileTabs />

            {/* Live Breaking News Ticker */}
            <BreakingTicker articles={breakingNews} />

            {/* Main Application Content */}
            <div className="site-container main-wrapper">
              {children}
            </div>

            {/* Modals & Drawers */}
            <QuickSearchModal />
            <MobileDrawer />
            <MobileBottomNav />

            {/* Footer */}
            <Footer />
          </UIProvider>
        </ThemeProvider>
      </body>
    </html>
  );
}
