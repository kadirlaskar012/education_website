-- ==========================================================================
-- INITIAL SEED DATA: CATEGORIES, SOURCES, SITE SETTINGS & ADMIN USER
-- ==========================================================================

-- 1. Default Admin User: admin / admin123
INSERT OR IGNORE INTO users (id, username, password_hash, email) VALUES 
(1, 'admin', '$2y$12$6P4v9d15i6fIqYf75YqY1eEGBw8aQZ6z.fQYd7V5h8X0hGqI5eMee', 'admin@edugovnews.in');

-- 2. Default Site Settings (with Auto-Publish & AI Rewrite enabled)
INSERT OR IGNORE INTO site_settings (id, site_name, site_tagline, contact_email, top_breaking_announcement, auto_publish, ai_rewrite, min_quality_score) VALUES
(1, 'EduGov News', 'Verified Official Education Updates & Notifications', 'contact@edugovnews.in', 'RRB NTPC 2026 Notification Out — Check Dates & Links', 1, 1, 80);

-- 3. 14 Official Education Categories
INSERT OR IGNORE INTO categories (id, name, slug, description, icon, display_order) VALUES
(1, 'Latest News', 'latest-news', 'General breaking education notices and central policy updates', '📰', 1),
(2, 'Results', 'results', 'Score cards, merit lists, cutoff marks, and qualifying candidate rosters', '📋', 2),
(3, 'Admit Card', 'admit-card', 'Hall tickets, exam city intimation slips, and shift timings', '🎫', 3),
(4, 'Recruitment', 'recruitment', 'Official employment notices, vacancy notifications, and online application links', '💼', 4),
(5, 'Exam', 'exam', 'Official exam dates, schedules, timing announcements, and session calendars', '📝', 5),
(6, 'Answer Key', 'answer-key', 'Provisional and final answer keys, response sheets, and objection windows', '🔑', 6),
(7, 'Admission', 'admission', 'University entrance tests, counseling schedules, and seat allotment lists', '🎓', 7),
(8, 'Scholarship', 'scholarship', 'State, central, and merit-based financial aid schemes', '🏆', 8),
(9, 'Exam Date', 'exam-date', 'Dedicated exam calendar and revised date sheets', '📅', 9),
(10, 'Application Form', 'application-form', 'Direct online registration portals, corrections, and fee deadlines', '📑', 10),
(11, 'Board Exams', 'board-exams', 'CBSE, ICSE, and State Board secondary/higher secondary notifications', '🏫', 11),
(12, 'Entrance Exams', 'entrance-exams', 'JEE Main/Advanced, NEET, CUET, GATE, CAT, and national entrance tests', '🎯', 12),
(13, 'Government Jobs', 'government-jobs', 'Central, state, defense, banking, and public sector employment notifications', '🏛️', 13),
(14, 'Important Updates', 'important-updates', 'Urgent advisories, syllabus revisions, and policy notices', '⚡', 14);

-- 4. Official Sources & Adapters
INSERT OR IGNORE INTO sources (id, name, slug, authority_name, source_type, adapter_class, base_url, notices_url, default_category_id, is_active, is_trusted) VALUES
(1, 'Staff Selection Commission (SSC)', 'ssc-official', 'Staff Selection Commission', 'custom_html', 'SSCAdapter', 'https://ssc.gov.in', 'https://ssc.gov.in/api/notices', 4, 1, 1),
(2, 'Union Public Service Commission (UPSC)', 'upsc-official', 'Union Public Service Commission', 'custom_html', 'UPSCAdapter', 'https://upsc.gov.in', 'https://upsc.gov.in/whats-new', 4, 1, 1),
(3, 'National Testing Agency (NTA)', 'nta-official', 'National Testing Agency', 'custom_html', 'NTAAdapter', 'https://nta.ac.in', 'https://nta.ac.in/NoticeArchive', 5, 1, 1),
(4, 'Railway Recruitment Boards (RRB)', 'rrb-official', 'Railway Recruitment Boards', 'custom_html', 'RailwayAdapter', 'https://rrbcdg.gov.in', 'https://rrbcdg.gov.in/active-notices.php', 4, 1, 1);
