# 🎓 EduGov News — Automated Education News & Notifications Portal

A modern, high-performance, automated Education News & Notification Portal built with **PHP 8+ & MySQL / SQLite**. Designed specifically for **ultra-low-cost shared hosting (Hostinger, cPanel, Namecheap at ₹80–₹120/month)** with zero server maintenance, automated government notice scrapers, SHA-256 deduplication, and an editorial news layout.

---

## 🌟 Key Features

1. **Ultra-Low Cost Shared Hosting Compatibility**:
   - Runs natively on **any standard cPanel or Apache/Nginx shared hosting** with PHP 8.0+.
   - No Docker, Celery, Redis, or VPS server configurations required.
2. **Dual-Mode Database**:
   - **Local Development**: Runs instantly with **SQLite** out of the box with zero setup (`php -S 127.0.0.1:8000 -t public`).
   - **Production (cPanel / Live)**: Seamlessly connects to **MySQL / MariaDB** via standard PDO.
3. **Automated Scraper Pipeline & SHA-256 Deduplication**:
   - Automatically checks official government portals (SSC, UPSC, NTA, RRB) for new recruitment, results, admit cards, and exam dates.
   - Computes cryptographic SHA-256 content hashes to prevent duplicate notices.
4. **Editorial & Responsive Design**:
   - Desktop Navigation Bar with Primary Hubs + "More Categories ▾" Dropdown.
   - **Mobile Category Ribbon**: Horizontal swipeable chip bar (`🏠 All`, `📋 Results`, `🎫 Admit Cards`, `💼 Recruitment`, `📝 Exams`, `🔑 Answer Key`).
   - **Mobile Slide-Over Drawer**: Offcanvas navigation with frosted backdrop blur and built-in search.
   - Touch-scrollable responsive data tables for Important Dates, Vacancies, and Direct Links.
5. **SEO & Discovery Engine**:
   - Auto-generated **XML Sitemap** (`/sitemap.xml`)
   - **RSS 2.0 Feed** (`/rss.xml`)
   - **Robots.txt** (`/robots.txt`)
   - Google News & Schema.org JSON-LD Structured Data on every article.
6. **Secure Admin Control Center**:
   - Access at `/admin` (Default: `admin` / `admin123`).
   - Live metrics (Total articles, published, review drafts, duplicate notices skipped, active scrapers).
   - One-click "⚡ Fetch & Scrape Sources Now" trigger.
   - Article editor and source management.

---

## 🚀 Local Development Setup (Quick Start)

### Requirements:
- PHP 8.0 or higher (with `pdo_sqlite`, `pdo_mysql`, `curl`, `mbstring`, `openssl` enabled in `php.ini`).

### 1. Run the Development Server
```bash
php -S 127.0.0.1:8000 -t public
```

Open your browser and visit:
- **Portal**: [http://127.0.0.1:8000](http://127.0.0.1:8000)
- **Admin Control Center**: [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin) *(Username: `admin`, Password: `admin123`)*

---

## 🛠️ CLI Terminal Commands

You can run automated tasks or inspect the database directly from the terminal:

```bash
# Seed initial 14 categories and official sources
php cli.php seed

# Run automated scraper pipeline manually
php cli.php run-pipeline

# View article inventory & database statistics
php cli.php stats
```

---

## 🌐 cPanel / Hostinger Shared Hosting Deployment Guide

### Step 1: Upload Files
1. Compress the project folder into a `.zip` archive.
2. In your cPanel / Hostinger File Manager, navigate to `public_html/` and extract the files.

### Step 2: Create MySQL Database & Import Schema
1. In cPanel, open **MySQL Database Wizard**.
2. Create a database (e.g. `youruser_edugov`) and a user with full privileges.
3. Open **phpMyAdmin**, select your database, and import:
   - `database/schema.sql` (Creates all tables and indexes)
   - `database/seed.sql` (Inserts categories, sources, and admin account)

### Step 3: Configure Database Credentials
Open `config/config.php` (or create a `.env` file) and update your MySQL settings:

```php
'database' => [
    'driver' => 'mysql',
    'mysql' => [
        'host'     => 'localhost',
        'port'     => '3306',
        'database' => 'youruser_edugov',
        'username' => 'youruser_dbuser',
        'password' => 'your_db_password',
        'charset'  => 'utf8mb4',
    ],
],
```

### Step 4: Set up Automated 15-Minute Cron Job
In cPanel, open the **Cron Jobs** tool and add the following scheduled task:

- **Schedule**: Every 15 minutes (`*/15 * * * *`)
- **Command**:
  ```bash
  php /home/yourusername/public_html/public/cron.php >/dev/null 2>&1
  ```

*(Replace `/home/yourusername/public_html/` with your actual server document root path shown in cPanel File Manager).*

That's it! Your education news portal is now live and will automatically fetch, process, and publish official notices 24/7.

---

## 📁 Directory Structure

```
education_website/
├── app/
│   ├── Controllers/          # HomeController, ArticleController, CategoryController, etc.
│   ├── Core/                 # Router, Controller, Model, Auth, View engines
│   ├── Models/               # Article, Category, Source, SourceItem, SiteSetting
│   ├── Pipeline/             # Scraper Adapters (SSC, UPSC, NTA, RRB) & HttpFetcher
│   └── Views/                # Layouts, Portal views, Legal pages & Admin panel
├── config/
│   ├── config.php            # Environment & Database settings
│   ├── database.php          # PDO DB Connection Manager
│   └── routes.php            # SEO-friendly clean routing table
├── database/
│   ├── schema.sql            # MySQL schema for cPanel import
│   ├── seed.sql              # 14 categories & official sources
│   └── database.sqlite       # Local SQLite development database
├── public/
│   ├── index.php             # Front Controller
│   ├── cron.php              # Automated 15-minute cPanel cron runner
│   ├── .htaccess             # Apache URL rewrite rules
│   └── static/               # Responsive CSS, Vanilla JS, and Admin assets
├── cli.php                   # Terminal CLI tool
├── .htaccess                 # Root cPanel public directory rewrite
└── README.md                 # Complete documentation
```

---

## 📄 License & Attribution
Data is fetched strictly from public domain government notices and examination boards (.gov.in, .nic.in, .ac.in). Editorial layout and automation engine © 2026.
