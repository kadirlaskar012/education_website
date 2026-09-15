# 🚀 EduGov News Portal — Complete Production Installation & Hosting Guide

Welcome to **EduGov News**, a high-performance, automated education news, examination dates, admit cards, results, and government recruitment portal built on native PHP 8+ & MySQL / SQLite.

---

## 📋 Server Requirements
* **PHP Version:** PHP 8.1, 8.2, 8.3 or higher
* **PHP Extensions:** `pdo`, `pdo_mysql` (or `pdo_sqlite`), `curl`, `mbstring`, `json`, `xml`, `openssl`
* **Web Server:** Apache (with `mod_rewrite` enabled), Nginx, or LiteSpeed
* **Database:** MySQL 5.7+ / MariaDB 10.3+ (or SQLite 3 for zero-config hosting)

---

## 🛠️ Step-by-Step cPanel Deployment Guide

### Step 1: Upload the ZIP File
1. Log into your **cPanel** account.
2. Open **File Manager** and navigate to your domain's document root (usually `public_html` or a subdomain folder like `public_html/news`).
3. Click **Upload** and upload `edugov_production_v2.zip`.
4. Right-click on the uploaded zip file and click **Extract**.

---

### Step 2: Database Setup (MySQL via cPanel & phpMyAdmin)
1. In cPanel, open **MySQL Database Wizard**:
   * Create a new database (e.g., `youruser_edugov`).
   * Create a new database user and assign a strong password.
   * Add the user to the database with **ALL PRIVILEGES**.
2. Open **phpMyAdmin**:
   * Select your newly created database (`youruser_edugov`).
   * Click the **Import** tab at the top.
   * Choose the file: `database/mysql_schema.sql` from your extracted files.
   * Click **Go / Import**. (All tables, categories, 26 government boards, and default settings will be imported instantly).

---

### Step 3: Configure Database & Domain URL
Open and edit the file **`config/config.php`** using cPanel File Editor:

```php
return [
    'app_name' => 'EduGov News',
    'app_url'  => 'https://yourdomain.com', // Enter your live domain with https://
    'env'      => 'production',
    'debug'    => false,

    'database' => [
        'driver'   => 'mysql', // Set driver to 'mysql'
        
        'mysql' => [
            'host'     => 'localhost',
            'port'     => '3306',
            'database' => 'youruser_edugov',   // Your cPanel Database Name
            'username' => 'youruser_dbuser',   // Your cPanel Database User
            'password' => 'Your_Strong_Password_Here', // Your Database Password
            'charset'  => 'utf8mb4',
        ],
    ],
];
```

---

### Step 4: Administrator Login & Settings
1. Open your web browser and go to: `https://yourdomain.com/admin`
2. **Default Credentials:**
   * **Username:** `admin`
   * **Password:** `admin123`
3. After signing in, go to **Settings** (`/admin/settings`):
   * Change your admin password immediately.
   * Enter your **Google Gemini API Key** (optional, for human-tone rewriting).
   * Enter your **Telegram Bot Token** and **Channel Username** (for automated social broadcasting).
   * Enter your **Google Analytics (GA4)** ID.

---

### Step 5: Setup 24/7 100% Automated Cron Job
To enable the website to automatically scrape 26 government portals, generate news, and auto-post to Telegram without any manual intervention:

#### Option A: cPanel Cron Job
1. In cPanel, search for **Cron Jobs**.
2. Select Interval: **Once Per 30 Minutes** (`*/30 * * * *`) or **Once Per Hour** (`0 * * * *`).
3. Enter Command:
   ```bash
   php /home/yourcpanelusername/public_html/cron/run_worker.php all > /dev/null 2>&1
   ```
4. Click **Add New Cron Job**.

#### Option B: Free Cloud Cron (Cron-Job.org — No Server Terminal Needed)
1. Go to [https://cron-job.org](https://cron-job.org) and register a free account.
2. Click **Create Cronjob**.
3. URL: `https://yourdomain.com/api/cron/run?key=edugov_auto_cron_secret_2026`
4. Schedule: **Every 30 Minutes**.
5. Save!

---

### Step 6: Search Engine & SEO Setup
1. **Google Search Console:** Submit `https://yourdomain.com/sitemap.xml` and `https://yourdomain.com/news-sitemap.xml`.
2. **Google Publisher Center:** Submit your publication with `https://yourdomain.com/news-sitemap.xml` to list your portal in Google News.
3. **IndexNow:** Instant indexing with Bing & Yandex is already active out of the box (`/indexnow.txt`).

---

### 🛡️ File Permissions Check
Ensure the following directories have write permissions (chmod `0755` or `0775`):
* `storage/`
* `database/` (if using SQLite)

---
*Support & Maintenance: EduGov Native Clean Architecture v2.0*
