# 🎓 EduGov News — Automated Human-Tone Article + SEO Engine (Plain PHP)

A production-ready, high-performance, automated Education News & Notification Portal built with **pure plain PHP & MySQL / SQLite**. Designed specifically for **ultra-low-cost shared hosting (Hostinger, cPanel, Namecheap at ₹80–₹120/month)** with zero framework overhead, zero-hallucination fact protection, **Google Gemini AI human-tone rewriting**, 10-point pre-publish quality checks, automated contextual internal linking, and rich Schema.org SEO structured data.

---

## 🌟 Key Capabilities

1. **Pure Plain PHP Architecture**:
   - Built with native PHP (cURL, DOMDocument, DOMXPath, PDO, OpenSSL).
   - No Laravel, Node.js, Next.js, Celery, or Docker required.
2. **Zero-Hallucination Fact-Protection Engine**:
   - Scrapes official notices and extracts verified structured facts (`organization`, `exam_name`, `dates`, `fees`, `vacancies`, `eligibility`, `links`) *before* sending to AI.
   - The AI writes strictly from verified facts. If information is missing: *"Not specified in the official notification."*
3. **Natural Human-Tone Rewriting (Google Gemini API + Deterministic Fallback)**:
   - Uses simple English, short paragraphs (2-3 sentences), varied sentence structures, and active voice.
   - Anti-robotic rules eliminate spin clichés (*"Candidates are advised to..."*, *"In a recent announcement..."*, etc.).
   - Robust fallback ensures the system works 24/7 even without an API key or during network downtime.
4. **Dynamic Article Templates**:
   - **Result**: Headline, Status Box, Scorecard Schedule Table, Step-by-Step Check Guide, Direct Links, FAQs.
   - **Recruitment**: Vacancy Breakdown, Eligibility, Age Limit, Application Fee, Schedule, How to Apply, Direct Links.
   - **Admit Card**: Release Status, Exam Dates, Shift Timings, Download Steps, Required Details, Direct Links.
5. **Advanced SEO & Rich Structured Data (JSON-LD)**:
   - Automated Natural SEO Titles (<= 65 chars) & Meta Descriptions (140-160 chars).
   - Auto-generated **Schema.org `@graph`**:
     - `NewsArticle` Schema
     - `BreadcrumbList` Schema
     - `FAQPage` Schema
   - Auto-generated **XML Sitemap** (`/sitemap.xml`) & **RSS 2.0 Feed** (`/rss.xml`).
6. **Automatic Contextual Internal Linking**:
   - Contextually links related official updates (e.g. SSC Result -> SSC Admit Card, SSC Exam Schedule, SSC Recruitment).
7. **10-Point Pre-Publish Quality Gatekeeper**:
   - Validates title, content thickness, table presence, valid URLs, placeholder detection, SEO metadata, and slug uniqueness.
   - Scores articles (0-100). Auto-publishes if >= 80% (configurable); otherwise flags for editorial `review`.
8. **Update Detection & Version History**:
   - Detects when government notices are updated.
   - Preserves canonical URL slugs and logs previous version snapshots in `article_versions`.
9. **Single-Line cPanel Cron Automation**:
   - `cron/run_all.php` runs the entire pipeline every 15 minutes with zero server daemons.

---

## 🚀 Local Development (Quick Start)

### 1. Run Built-in PHP Server
```bash
php -S 127.0.0.1:8000 -t public
```

- **Portal**: [http://127.0.0.1:8000](http://127.0.0.1:8000)
- **Admin Control Center**: [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin) *(Username: `admin`, Password: `admin123`)*

---

## 🛠️ CLI Terminal Commands

```bash
# Seed initial 14 categories and official sources
php cli.php seed

# Run the complete AI scraper & auto-publishing pipeline manually
php cli.php run-pipeline

# Run individual pipeline stages:
php cli.php fetch-sources      # Step 1: Scrape active sources
php cli.php process-articles   # Step 2: Fact extraction & AI rewriting
php cli.php publish-articles   # Step 3: Quality validation & auto-publish

# View database & quality statistics
php cli.php stats
```

---

## 🌐 cPanel / Hostinger Shared Hosting Deployment Guide

### Step 1: Upload Files
1. Compress this project folder into `.zip`.
2. In cPanel File Manager, upload to `public_html/` and extract.

### Step 2: Create MySQL Database & Import Schema
1. In cPanel, open **MySQL Database Wizard** and create a database + user.
2. In **phpMyAdmin**, import `database/schema.sql` and `database/seed.sql`.

### Step 3: Configure Database Credentials & AI Key
In `config/config.php` (or in `.env`), set your MySQL details:

```php
'database' => [
    'driver' => 'mysql',
    'mysql' => [
        'host'     => 'localhost',
        'port'     => '3306',
        'database' => 'youruser_edugov',
        'username' => 'youruser_dbuser',
        'password' => 'your_password',
        'charset'  => 'utf8mb4',
    ],
],
'ai' => [
    'api_key' => 'YOUR_GEMINI_API_KEY', // Optional, from Google AI Studio
],
```

### Step 4: Set up Automated 15-Minute Cron Job
In cPanel, open **Cron Jobs** and add:

- **Schedule**: Every 15 minutes (`*/15 * * * *`)
- **Command**:
  ```bash
  php /home/yourusername/public_html/cron/run_all.php >/dev/null 2>&1
  ```

---

## 📁 Modular Directory Structure

```
education_website/
├── app/
│   ├── Controllers/               # HomeController, ArticleController, AdminController, etc.
│   ├── Core/                      # Router, Controller, Model, Auth, View engines
│   ├── Models/                    # Article, ArticleVersion, Category, Source, SourceItem, SiteSetting
│   ├── Pipeline/
│   │   ├── Scraper/
│   │   │   ├── SourceFetcher.php      # Safe cURL fetcher with user-agent rotation
│   │   │   ├── HTMLParser.php         # DOMDocument & XPath notice extractor
│   │   │   ├── FactExtractor.php      # Grounded fact extractor (dates, fees, vacancies)
│   │   │   └── DuplicateChecker.php   # Multi-tier duplicate check (URL, Title, SHA-256)
│   │   ├── AI/
│   │   │   ├── GeminiClient.php       # Google Gemini REST API client
│   │   │   ├── PromptBuilder.php      # Human-tone zero-hallucination prompt builder
│   │   │   ├── ArticleGenerator.php   # Master AI rewriter + Fallback generator
│   │   │   └── SEOGenerator.php       # Natural SEO Titles, Meta Descriptions & Schema.org JSON-LD
│   │   ├── Quality/
│   │   │   ├── ArticleValidator.php   # 10-point pre-publish quality gatekeeper
│   │   │   └── InternalLinker.php     # Contextual related article internal linker
│   │   └── Services/
│   │       ├── PipelineRunner.php     # Master pipeline orchestrator
│   │       └── UpdateDetector.php     # Notice update detector & version logger
│   └── Views/
│       ├── layouts/                   # main.php (with mobile smart tabs & bottom dock), admin.php
│       ├── portal/                    # home, category, article_detail, search, legal pages
│       └── admin/                     # dashboard, login, articles, article_edit, settings, sources
├── config/
│   ├── config.php                 # Environment, Database & AI settings
│   ├── database.php               # PDO DB connection manager
│   └── routes.php                 # SEO-friendly clean routing table
├── cron/
│   ├── fetch_sources.php          # Step 1: Scrape active sources
│   ├── process_articles.php       # Step 2: Fact extraction & AI rewriting
│   ├── publish_articles.php       # Step 3: Quality check & auto-publishing
│   └── run_all.php                # Unified master cron runner for cPanel
├── database/
│   ├── schema.sql                 # MySQL/SQLite schema with fact tracking & versions
│   └── seed.sql                   # 14 categories, official sources & site settings
├── public/
│   ├── index.php                  # Front Controller
│   ├── cron.php                   # Web/CLI cron entrypoint
│   ├── .htaccess                  # Apache URL rewrite rules
│   └── static/                    # Responsive CSS, Vanilla JS & Admin assets
├── cli.php                        # Terminal CLI tool
├── .htaccess                      # Root cPanel public directory rewrite
└── README.md                      # Complete documentation
```
