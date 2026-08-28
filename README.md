# EduGov News — Automated Education News & Information Website

A production-ready, minimal, high-performance **Automated Education News & Information Portal** built with **Python, Django, Celery, Redis, and PostgreSQL**.

The system automatically monitors, fetches, parses, classifies, deduplicates, and publishes official education circulars, exam dates, recruitment notices, admit cards, and results from government portals (SSC, UPSC, NTA, RRB, CBSE, State Boards) into clean, modern news articles with strict fact-grounding.

---

## 🏛️ System Architecture

```
                               ┌─────────────────────────────────┐
                               │   Official Government Portals   │
                               │   (SSC, UPSC, NTA, RRB, RSS)    │
                               └────────────────┬────────────────┘
                                                │
                                    [1. Source Scraper / Fetcher]
                                                │
                                   [2. SHA-256 Change Detector]
                                                │
                                  [3. PDF & Notice Text Parser]
                                                │
                                   [4. Duplicate & Update Check]
                                                │
                                     [5. Structured Generator]
                                                │
                                       [6. Data Validator]
                                                │
                         ┌──────────────────────┴──────────────────────┐
                         │                                             │
             (Auto-Publish Enabled)                          (Admin Review Mode)
                         │                                             │
                 [Status: Published]                           [Status: Review]
                         │                                             │
                         └──────────────────────┬──────────────────────┘
                                                │
                                 ┌──────────────┴──────────────┐
                                 │      Web & Public Portal    │
                                 │  • Fast SSR Clean News UI   │
                                 │  • Structured Tables & FAQ  │
                                 │  • Schema.org JSON-LD & RSS │
                                 │  • Admin Control Dashboard  │
                                 └─────────────────────────────┘
```

---

## 🚀 Key Features

* **Clean News Portal Aesthetics**: Editorial white/light layout, deep navy accents, thin dividers, compact news stream, mobile-first drawer navigation.
* **Modular Adapter Architecture**: Extensible adapters (`SSCAdapter`, `UPSCAdapter`, `NTAAdapter`, `RailwayAdapter`, `GenericRSSAdapter`, `GenericHTMLAdapter`) that plug in effortlessly.
* **Automated PDF Parsing**: Extracts exam dates, vacancies, application deadlines, eligibility criteria, and fee structures from official PDF releases.
* **Strict Fact-Grounding**: Never hallucinates or invents dates or details. Unspecified items are marked *"Not specified in the official notification"*.
* **Duplicate & Update Detection**: SHA-256 hash tracking. Detects amendments and saves `ArticleVersion` revision histories with original canonical URLs preserved.
* **SEO & Discovery**: Automated Schema.org JSON-LD (`NewsArticle`, `FAQPage`, `BreadcrumbList`), dynamic XML sitemap (`/sitemap.xml`), and RSS 2.0 feed (`/rss.xml`).
* **Background Automation**: Celery + Redis + Celery Beat schedules periodic scraping runs without blocking web requests.
* **Full Django Admin Control**: Live metrics dashboard, Source Health monitoring, and 1-click manual fetch triggers.

---

## 🛠️ Local Development & Quickstart

### 1. Install Dependencies
```bash
python -m pip install -r requirements.txt
```

### 2. Apply Migrations
```bash
python manage.py makemigrations
python manage.py migrate
```

### 3. Seed Initial Categories, Templates, Sources & Live Articles
```bash
python manage.py seed_education_data
```

### 4. Create Superuser (for Admin Access)
```bash
python manage.py createsuperuser
```

### 5. Run the Local Development Server
```bash
python manage.py runserver 8000
```
Open [http://127.0.0.1:8000/](http://127.0.0.1:8000/) in your browser.

---

## ⚙️ Background Automation & CLI Commands

### Run Full Ingestion Pipeline via CLI
```bash
# Ingest from all active sources
python manage.py run_pipeline --all

# Ingest from a specific source ID
python manage.py run_pipeline --source 1
```

### Test an Adapter Directly
```bash
python manage.py test_adapter ssc
python manage.py test_adapter upsc
python manage.py test_adapter nta
python manage.py test_adapter railway
```

### Run Celery Worker & Beat (Production Automation)
```bash
# Start Celery Worker
celery -A core worker -l info

# Start Celery Periodic Scheduler (Beat)
celery -A core beat -l info
```

---

## 🐳 Docker Deployment

To launch the complete production stack (Django Web + PostgreSQL + Redis + Celery Worker + Celery Beat):

```bash
docker compose up -d --build
```

The website will be accessible on `http://localhost:8000/`.

---

## 🧩 Adding New Government Sources

Adding a new government source requires zero core refactoring:

1. Log in to `/admin/` & navigate to **Sources**.
2. Click **Add Source**:
   - **Name**: e.g., `CBSE Academic Notices`
   - **URL**: `https://www.cbse.gov.in/`
   - **Official Domain**: `cbse.gov.in`
   - **Parser**: Select existing adapter or `generic_html` / `generic_rss`
   - **Fetch Frequency**: `15` (minutes)
   - **Auto Publish**: `True` (or `False` for review mode)
3. Click **Save** and click **Fetch Now** to immediately trigger ingestion!
