-- ==========================================================================
-- EDUGOV NEWS & NOTIFICATIONS PORTAL — PURE MYSQL SCHEMA (cPanel / phpMyAdmin)
-- Character Set: utf8mb4 / utf8mb4_unicode_ci
-- ==========================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Categories Table
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(120) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `icon` VARCHAR(20) DEFAULT '📰',
    `display_order` INT DEFAULT 0,
    `is_active` TINYINT DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Sources Table
CREATE TABLE IF NOT EXISTS `sources` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `slug` VARCHAR(150) NOT NULL UNIQUE,
    `authority_name` VARCHAR(150) NOT NULL,
    `state_code` VARCHAR(20) DEFAULT 'ALL',
    `state_name` VARCHAR(100) DEFAULT 'All India / Central',
    `source_type` VARCHAR(50) NOT NULL DEFAULT 'custom_html',
    `adapter_class` VARCHAR(100) NOT NULL,
    `base_url` VARCHAR(255) NOT NULL,
    `notices_url` VARCHAR(255) NOT NULL,
    `default_category_id` INT NULL,
    `is_active` TINYINT DEFAULT 1,
    `is_trusted` TINYINT DEFAULT 1,
    `fetch_interval_minutes` INT DEFAULT 15,
    `last_fetched_at` DATETIME NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_sources_category` FOREIGN KEY (`default_category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Source Items Table
CREATE TABLE IF NOT EXISTS `source_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `source_id` INT NOT NULL,
    `external_id` VARCHAR(255) NULL,
    `source_title` TEXT NOT NULL,
    `source_url` VARCHAR(500) NOT NULL,
    `source_pdf_url` VARCHAR(500) NULL,
    `source_date` VARCHAR(50) NULL,
    `source_content` TEXT NULL,
    `source_hash` VARCHAR(64) NOT NULL UNIQUE,
    `title_hash` VARCHAR(64) NOT NULL,
    `extracted_data` LONGTEXT NULL,
    `status` VARCHAR(50) DEFAULT 'new',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_source_items_source` FOREIGN KEY (`source_id`) REFERENCES `sources` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Articles Table
CREATE TABLE IF NOT EXISTS `articles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `source_item_id` INT NULL,
    `category_id` INT NOT NULL,
    `title` VARCHAR(300) NOT NULL,
    `slug` VARCHAR(350) NOT NULL UNIQUE,
    `meta_description` VARCHAR(350) NULL,
    `summary` TEXT NULL,
    `excerpt` VARCHAR(350) NULL,
    `content_html` LONGTEXT NOT NULL,
    `structured_data` LONGTEXT NULL,
    `schema_json` LONGTEXT NULL,
    `internal_links_json` LONGTEXT NULL,
    `official_source_name` VARCHAR(150) NULL,
    `official_source_url` VARCHAR(500) NULL,
    `official_pdf_url` VARCHAR(500) NULL,
    `is_breaking` TINYINT DEFAULT 0,
    `is_featured` TINYINT DEFAULT 0,
    `views_count` INT DEFAULT 0,
    `quality_score` INT DEFAULT 100,
    `validation_notes` TEXT NULL,
    `status` VARCHAR(50) DEFAULT 'published',
    `version_number` INT DEFAULT 1,
    `published_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_articles_source_item` FOREIGN KEY (`source_item_id`) REFERENCES `source_items` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_articles_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Article Versions Table
CREATE TABLE IF NOT EXISTS `article_versions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `article_id` INT NOT NULL,
    `version_number` INT NOT NULL,
    `change_summary` VARCHAR(255) NULL,
    `title` VARCHAR(300) NULL,
    `content_snapshot` LONGTEXT NULL,
    `structured_snapshot` LONGTEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_article_versions_article` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Site Settings Table
CREATE TABLE IF NOT EXISTS `site_settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `site_name` VARCHAR(100) DEFAULT 'EduGov News',
    `site_tagline` VARCHAR(255) DEFAULT 'Verified Official Education Updates & Notifications',
    `contact_email` VARCHAR(150) DEFAULT 'contact@edugovnews.in',
    `top_breaking_announcement` VARCHAR(255) DEFAULT 'RRB NTPC 2026 Notification Out — Check Dates & Links',
    `auto_publish` TINYINT DEFAULT 1,
    `ai_rewrite` TINYINT DEFAULT 1,
    `gemini_api_key` VARCHAR(255) DEFAULT '',
    `min_quality_score` INT DEFAULT 80,
    `telegram_bot_token` VARCHAR(255) DEFAULT '',
    `telegram_channel_id` VARCHAR(100) DEFAULT '',
    `telegram_auto_post` TINYINT DEFAULT 0,
    `facebook_page_id` VARCHAR(100) DEFAULT '',
    `facebook_access_token` TEXT NULL,
    `facebook_auto_post` TINYINT DEFAULT 0,
    `twitter_webhook_url` VARCHAR(500) DEFAULT '',
    `twitter_auto_post` TINYINT DEFAULT 0,
    `ga4_measurement_id` VARCHAR(50) DEFAULT '',
    `google_site_verification` VARCHAR(100) DEFAULT '',
    `cron_secret_key` VARCHAR(100) DEFAULT 'edugov_auto_cron_secret_2026',
    `ad_header_html` TEXT NULL,
    `ad_in_article_html` TEXT NULL,
    `ad_sidebar_html` TEXT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Users Table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(80) NOT NULL UNIQUE,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` VARCHAR(50) DEFAULT 'admin',
    `is_active` TINYINT DEFAULT 1,
    `last_login_at` DATETIME NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Audit Logs Table
CREATE TABLE IF NOT EXISTS `users_audit` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL,
    `action` VARCHAR(100) NOT NULL,
    `details` TEXT NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` VARCHAR(255) NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------------
-- SEED DATA: Default Admin Account & Settings
-- Default Login: admin / admin123
-- --------------------------------------------------------------------------
INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `is_active`)
VALUES (1, 'admin', 'admin@edugovnews.in', '$2y$12$eUu9e2B3/Gskz9bT9W090.r3LzO4KkJ8iW17z5z46wYh9V2qN.O.a', 'admin', 1)
ON DUPLICATE KEY UPDATE `id`=`id`;

INSERT INTO `site_settings` (`id`, `site_name`, `site_tagline`, `contact_email`, `auto_publish`, `ai_rewrite`, `gemini_api_key`, `telegram_bot_token`, `telegram_channel_id`, `telegram_auto_post`, `cron_secret_key`)
VALUES (1, 'EduGov News', 'Verified Official Education Updates & Notifications', 'contact@edugovnews.in', 1, 1, '', '', '', 0, 'edugov_auto_cron_secret_2026')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- --------------------------------------------------------------------------
-- SEED DATA: Categories
-- --------------------------------------------------------------------------
INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `display_order`) VALUES
(1, 'Results', 'results', '📋', 1),
(2, 'Admit Card', 'admit-card', '🎫', 2),
(3, 'Recruitment', 'recruitment', '💼', 3),
(4, 'Exam Dates', 'exam', '📝', 4),
(5, 'Answer Key', 'answer-key', '🔑', 5),
(6, 'Scholarships', 'scholarship', '🏆', 6),
(7, 'Admissions', 'admission', '🎓', 7),
(8, 'Board Exams', 'board-exams', '🏫', 8),
(9, 'Entrance Exams', 'entrance-exams', '🎯', 9),
(10, 'Government Jobs', 'government-jobs', '🏛️', 10),
(11, 'Application Forms', 'application-form', '📑', 11),
(12, 'Syllabus & Pattern', 'syllabus', '📚', 12),
(13, 'University Updates', 'university', '🏛️', 13),
(14, 'General Notices', 'general', '📢', 14)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- --------------------------------------------------------------------------
-- SEED DATA: 26 Verified Government Boards & Commissions
-- --------------------------------------------------------------------------
INSERT INTO `sources` (`id`, `name`, `slug`, `authority_name`, `state_code`, `state_name`, `source_type`, `adapter_class`, `base_url`, `notices_url`, `default_category_id`) VALUES
(1, 'Staff Selection Commission (SSC)', 'ssc', 'Staff Selection Commission', 'ALL', 'All India / Central', 'custom_html', 'App\\Pipeline\\Adapters\\Central\\SSCAdapter', 'https://ssc.gov.in', 'https://ssc.gov.in/api/notices', 3),
(2, 'Union Public Service Commission (UPSC)', 'upsc', 'Union Public Service Commission', 'ALL', 'All India / Central', 'custom_html', 'App\\Pipeline\\Adapters\\Central\\UPSCAdapter', 'https://upsc.gov.in', 'https://upsc.gov.in/whats-new', 3),
(3, 'Railway Recruitment Boards (RRB)', 'rrb', 'Railway Recruitment Control Board', 'ALL', 'All India / Central', 'custom_html', 'App\\Pipeline\\Adapters\\Central\\RRBAdapter', 'https://www.rrbcdg.gov.in', 'https://www.rrbcdg.gov.in', 3),
(4, 'Institute of Banking Personnel Selection (IBPS)', 'ibps', 'IBPS', 'ALL', 'All India / Central', 'custom_html', 'App\\Pipeline\\Adapters\\Central\\IBPSAdapter', 'https://www.ibps.in', 'https://www.ibps.in', 3),
(5, 'National Testing Agency (NTA)', 'nta', 'National Testing Agency', 'ALL', 'All India / Central', 'custom_html', 'App\\Pipeline\\Adapters\\Central\\NTAAdapter', 'https://nta.ac.in', 'https://nta.ac.in/NoticeArchive', 9),
(6, 'State Bank of India Careers (SBI)', 'sbi', 'State Bank of India', 'ALL', 'All India / Central', 'custom_html', 'App\\Pipeline\\Adapters\\Central\\SBIAdapter', 'https://sbi.co.in', 'https://sbi.co.in/web/careers/current-openings', 3),
(7, 'Central Board of Secondary Education (CBSE)', 'cbse', 'CBSE Board', 'ALL', 'All India / Central', 'custom_html', 'App\\Pipeline\\Adapters\\Central\\CBSEAdapter', 'https://www.cbse.gov.in', 'https://www.cbse.gov.in/cbsenew/cbse.html', 8),
(8, 'National Scholarship Portal (NSP)', 'nsp', 'Ministry of Electronics & IT', 'ALL', 'All India / Central', 'custom_html', 'App\\Pipeline\\Adapters\\Central\\NSPAdapter', 'https://scholarships.gov.in', 'https://scholarships.gov.in', 6),
(9, 'West Bengal Public Service Commission (WBPSC)', 'wbpsc', 'WBPSC', 'WB', 'West Bengal', 'custom_html', 'App\\Pipeline\\Adapters\\State\\WBPSCAdapter', 'https://psc.wb.gov.in', 'https://psc.wb.gov.in', 3),
(10, 'West Bengal Police Recruitment Board (WBPRB)', 'wbp', 'West Bengal Police', 'WB', 'West Bengal', 'custom_html', 'App\\Pipeline\\Adapters\\State\\WBPoliceAdapter', 'https://prb.wb.gov.in', 'https://prb.wb.gov.in', 3),
(11, 'West Bengal Board of Primary Education (WBBPE)', 'wbbpe', 'WBBPE', 'WB', 'West Bengal', 'custom_html', 'App\\Pipeline\\Adapters\\State\\WBBPEAdapter', 'https://wbbpe.org', 'https://wbbpe.org', 1),
(12, 'West Bengal Central School Service Commission (WBSSC)', 'wbssc', 'WBSSC', 'WB', 'West Bengal', 'custom_html', 'App\\Pipeline\\Adapters\\State\\WBSSCAdapter', 'http://www.westbengalssc.com', 'http://www.westbengalssc.com', 3),
(13, 'West Bengal Joint Entrance Examinations Board (WBJEEB)', 'wbjee', 'WBJEEB', 'WB', 'West Bengal', 'custom_html', 'App\\Pipeline\\Adapters\\State\\WBJEEAdapter', 'https://wbjeeb.nic.in', 'https://wbjeeb.nic.in', 9),
(14, 'Swami Vivekananda Scholarship (SVMCM)', 'svmcm', 'Govt of West Bengal Higher Education', 'WB', 'West Bengal', 'custom_html', 'App\\Pipeline\\Adapters\\State\\SVMCMAdapter', 'https://svmcm.wbhed.gov.in', 'https://svmcm.wbhed.gov.in', 6),
(15, 'Uttar Pradesh Public Service Commission (UPPSC)', 'uppsc', 'UPPSC Prayagraj', 'UP', 'Uttar Pradesh', 'custom_html', 'App\\Pipeline\\Adapters\\State\\UPPSCAdapter', 'https://uppsc.up.nic.in', 'https://uppsc.up.nic.in', 3),
(16, 'UP Subordinate Services Selection Commission (UPSSSC)', 'upsssc', 'UPSSSC Lucknow', 'UP', 'Uttar Pradesh', 'custom_html', 'App\\Pipeline\\Adapters\\State\\UPSSSCAdapter', 'http://upsssc.gov.in', 'http://upsssc.gov.in', 3),
(17, 'UP Police Recruitment & Promotion Board (UPPRPB)', 'upprpb', 'UP Police', 'UP', 'Uttar Pradesh', 'custom_html', 'App\\Pipeline\\Adapters\\State\\UPPoliceAdapter', 'http://uppbpb.gov.in', 'http://uppbpb.gov.in', 3),
(18, 'Bihar Public Service Commission (BPSC)', 'bpsc', 'BPSC Patna', 'BIHAR', 'Bihar', 'custom_html', 'App\\Pipeline\\Adapters\\State\\BPSCAdapter', 'https://www.bpsc.bih.nic.in', 'https://www.bpsc.bih.nic.in', 3),
(19, 'Bihar Staff Selection Commission (BSSC)', 'bssc', 'BSSC Patna', 'BIHAR', 'Bihar', 'custom_html', 'App\\Pipeline\\Adapters\\State\\BSSCAdapter', 'https://bssc.bihar.gov.in', 'https://bssc.bihar.gov.in', 3),
(20, 'Bihar Central Selection Board of Constable (CSBC)', 'csbc', 'CSBC Bihar', 'BIHAR', 'Bihar', 'custom_html', 'App\\Pipeline\\Adapters\\State\\CSBCAdapter', 'https://csbc.bih.nic.in', 'https://csbc.bih.nic.in', 3),
(21, 'Rajasthan Public Service Commission (RPSC)', 'rpsc', 'RPSC Ajmer', 'RAJ', 'Rajasthan', 'custom_html', 'App\\Pipeline\\Adapters\\State\\RPSCAdapter', 'https://rpsc.rajasthan.gov.in', 'https://rpsc.rajasthan.gov.in/news', 3),
(22, 'Rajasthan Staff Selection Board (RSMSSB)', 'rsmssb', 'RSMSSB Jaipur', 'RAJ', 'Rajasthan', 'custom_html', 'App\\Pipeline\\Adapters\\State\\RSMSSBAdapter', 'https://rsmssb.rajasthan.gov.in', 'https://rsmssb.rajasthan.gov.in/page?MenuKey=W1s8hS3M4jU=', 3),
(23, 'Madhya Pradesh Public Service Commission (MPPSC)', 'mppsc', 'MPPSC Indore', 'MP', 'Madhya Pradesh', 'custom_html', 'App\\Pipeline\\Adapters\\State\\MPPSCAdapter', 'https://mppsc.mp.gov.in', 'https://mppsc.mp.gov.in/whatsNew', 3),
(24, 'MP Employees Selection Board (MPESB / Vyapam)', 'mpesb', 'MPESB Bhopal', 'MP', 'Madhya Pradesh', 'custom_html', 'App\\Pipeline\\Adapters\\State\\MPESBAdapter', 'https://esb.mp.gov.in', 'https://esb.mp.gov.in', 3),
(25, 'Maharashtra Public Service Commission (MPSC)', 'mpsc', 'MPSC Mumbai', 'MAH', 'Maharashtra', 'custom_html', 'App\\Pipeline\\Adapters\\State\\MPSCAdapter', 'https://mpsc.gov.in', 'https://mpsc.gov.in', 3),
(26, 'Delhi Subordinate Services Selection Board (DSSSB)', 'dsssb', 'DSSSB Delhi', 'DELHI', 'Delhi (NCT)', 'custom_html', 'App\\Pipeline\\Adapters\\State\\DSSSBAdapter', 'https://dsssb.delhi.gov.in', 'https://dsssb.delhi.gov.in/current-vacancies', 3)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

SET FOREIGN_KEY_CHECKS = 1;
