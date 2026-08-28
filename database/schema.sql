-- ==========================================================================
-- EDUCATION NEWS & NOTIFICATIONS PORTAL DATABASE SCHEMA
-- Compatible with MySQL (cPanel) and SQLite (Local Development)
-- ==========================================================================

-- 1. Categories Table (14 Official Education News Categories)
CREATE TABLE IF NOT EXISTS categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    description TEXT,
    icon VARCHAR(20) DEFAULT '📰',
    display_order INTEGER DEFAULT 0,
    is_active INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 2. Sources Table (Official Government Portals & Exam Boards)
CREATE TABLE IF NOT EXISTS sources (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    authority_name VARCHAR(150) NOT NULL,
    source_type VARCHAR(50) NOT NULL DEFAULT 'custom_html', -- 'custom_html', 'rss', 'api'
    adapter_class VARCHAR(100) NOT NULL,
    base_url VARCHAR(255) NOT NULL,
    notices_url VARCHAR(255) NOT NULL,
    default_category_id INTEGER,
    is_active INTEGER DEFAULT 1,
    is_trusted INTEGER DEFAULT 1,
    fetch_interval_minutes INTEGER DEFAULT 15,
    last_fetched_at DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (default_category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- 3. Source Items Table (Raw scraped notices with SHA-256 deduplication)
CREATE TABLE IF NOT EXISTS source_items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    source_id INTEGER NOT NULL,
    external_id VARCHAR(255),
    raw_title TEXT NOT NULL,
    raw_url VARCHAR(500) NOT NULL,
    content_hash VARCHAR(64) NOT NULL UNIQUE, -- SHA-256 hash of content
    title_hash VARCHAR(64) NOT NULL,
    published_date VARCHAR(50),
    raw_html TEXT,
    status VARCHAR(50) DEFAULT 'pending', -- 'pending', 'processed', 'duplicate', 'error'
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (source_id) REFERENCES sources(id) ON DELETE CASCADE
);

-- 4. Articles Table (Published, Structured, Grounded News Articles)
CREATE TABLE IF NOT EXISTS articles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    source_item_id INTEGER,
    category_id INTEGER NOT NULL,
    template_type VARCHAR(50) DEFAULT 'general_news', -- 'result', 'admit_card', 'recruitment', 'exam_date', 'answer_key', 'general_news'
    title VARCHAR(300) NOT NULL,
    slug VARCHAR(350) NOT NULL UNIQUE,
    summary TEXT,
    excerpt VARCHAR(350),
    content_html TEXT NOT NULL,
    structured_data TEXT, -- JSON payload (dates, vacancies, fees, links, faq)
    official_source_name VARCHAR(150),
    official_source_url VARCHAR(500),
    is_breaking INTEGER DEFAULT 0,
    is_featured INTEGER DEFAULT 0,
    views_count INTEGER DEFAULT 0,
    status VARCHAR(50) DEFAULT 'published', -- 'draft', 'review', 'published', 'updated', 'rejected'
    version_number INTEGER DEFAULT 1,
    published_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (source_item_id) REFERENCES source_items(id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
);

-- 5. Article Versions Table (Update audit trail)
CREATE TABLE IF NOT EXISTS article_versions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    article_id INTEGER NOT NULL,
    version_number INTEGER NOT NULL,
    change_summary VARCHAR(255),
    content_snapshot TEXT,
    structured_snapshot TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
);

-- 6. Site Settings Table (Dynamic configuration & Ad placements)
CREATE TABLE IF NOT EXISTS site_settings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    site_name VARCHAR(100) DEFAULT 'EduGov News',
    site_tagline VARCHAR(255) DEFAULT 'Verified Official Education Updates & Notifications',
    contact_email VARCHAR(150) DEFAULT 'contact@edugovnews.in',
    top_breaking_announcement VARCHAR(255) DEFAULT 'RRB NTPC 2026 Notification Out — Check Dates & Links',
    ad_header_html TEXT,
    ad_in_article_html TEXT,
    ad_sidebar_html TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 7. Admin Users Table (For secure Control Center login)
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(150),
    is_active INTEGER DEFAULT 1,
    last_login DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 8. Fetch & Pipeline Logs Table (For observability)
CREATE TABLE IF NOT EXISTS pipeline_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    source_id INTEGER,
    status VARCHAR(50) NOT NULL, -- 'success', 'warning', 'error'
    items_found INTEGER DEFAULT 0,
    items_created INTEGER DEFAULT 0,
    items_updated INTEGER DEFAULT 0,
    items_duplicate INTEGER DEFAULT 0,
    error_message TEXT,
    execution_time_seconds REAL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (source_id) REFERENCES sources(id) ON DELETE SET NULL
);

-- Indexes for lightning fast queries
CREATE INDEX IF NOT EXISTS idx_articles_slug ON articles(slug);
CREATE INDEX IF NOT EXISTS idx_articles_status_pub ON articles(status, published_at);
CREATE INDEX IF NOT EXISTS idx_articles_category ON articles(category_id);
CREATE INDEX IF NOT EXISTS idx_source_items_hash ON source_items(content_hash);
