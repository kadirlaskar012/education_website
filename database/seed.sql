-- ==========================================================================
-- INITIAL SEED DATA: 14 CATEGORIES, 25+ INDIAN OFFICIAL SOURCES & SETTINGS
-- ==========================================================================

-- 1. Default Admin User: admin / admin123
INSERT OR IGNORE INTO users (id, username, password_hash, email) VALUES 
(1, 'admin', '$2y$12$6P4v9d15i6fIqYf75YqY1eEGBw8aQZ6z.fQYd7V5h8X0hGqI5eMee', 'admin@edugovnews.in');

-- 2. Default Site Settings
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

-- 4. 25+ Official Indian Government Sources
INSERT OR IGNORE INTO sources (id, name, slug, authority_name, state_code, state_name, source_type, adapter_class, base_url, notices_url, default_category_id, is_active, is_trusted) VALUES
-- Central Recruitment & Commissions
(1, 'Staff Selection Commission (SSC)', 'ssc-official', 'Staff Selection Commission', 'ALL', 'All India / Central', 'custom_html', 'SSCAdapter', 'https://ssc.gov.in', 'https://ssc.gov.in/api/notices', 4, 1, 1),
(2, 'Union Public Service Commission (UPSC)', 'upsc-official', 'Union Public Service Commission', 'ALL', 'All India / Central', 'custom_html', 'UPSCAdapter', 'https://upsc.gov.in', 'https://upsc.gov.in/whats-new', 4, 1, 1),
(3, 'Railway Recruitment Boards (RRB)', 'rrb-official', 'Railway Recruitment Boards', 'ALL', 'All India / Central', 'custom_html', 'RailwayAdapter', 'https://rrbcdg.gov.in', 'https://rrbcdg.gov.in/active-notices.php', 4, 1, 1),
(4, 'Institute of Banking Personnel Selection (IBPS)', 'ibps-official', 'Institute of Banking Personnel Selection', 'ALL', 'All India / Central', 'custom_html', 'BankingAdapter', 'https://ibps.in', 'https://ibps.in/notifications', 4, 1, 1),
(5, 'State Bank of India (SBI Careers)', 'sbi-careers', 'State Bank of India', 'ALL', 'All India / Central', 'custom_html', 'BankingAdapter', 'https://sbi.co.in/careers', 'https://sbi.co.in/web/careers/current-openings', 4, 1, 1),
(6, 'Reserve Bank of India (RBI Opportunities)', 'rbi-opportunities', 'Reserve Bank of India', 'ALL', 'All India / Central', 'custom_html', 'BankingAdapter', 'https://opportunities.rbi.org.in', 'https://opportunities.rbi.org.in/scripts/vacancies.aspx', 4, 1, 1),

-- National Entrance & Testing Agencies
(7, 'National Testing Agency (NTA)', 'nta-official', 'National Testing Agency', 'ALL', 'All India / Central', 'custom_html', 'NTAAdapter', 'https://nta.ac.in', 'https://nta.ac.in/NoticeArchive', 5, 1, 1),
(8, 'IIT JEE Advanced Organizing Institute', 'jee-advanced', 'Joint Admission Board (IITs)', 'ALL', 'All India / Central', 'custom_html', 'EntranceAdapter', 'https://jeeadv.ac.in', 'https://jeeadv.ac.in/notices', 12, 1, 1),
(9, 'All India Institute of Medical Sciences (AIIMS Exams)', 'aiims-exams', 'AIIMS Examination Section', 'ALL', 'All India / Central', 'custom_html', 'EntranceAdapter', 'https://aiimsexams.ac.in', 'https://aiimsexams.ac.in/notifications', 12, 1, 1),
(10, 'National Board of Examinations in Medical Sciences (NBEMS)', 'nbe-official', 'National Board of Examinations', 'ALL', 'All India / Central', 'custom_html', 'EntranceAdapter', 'https://natboard.edu.in', 'https://natboard.edu.in/notices', 12, 1, 1),
(11, 'Central Board of Secondary Education (CBSE)', 'cbse-official', 'Central Board of Secondary Education', 'ALL', 'All India / Central', 'custom_html', 'EntranceAdapter', 'https://cbse.gov.in', 'https://cbse.gov.in/newsite/index.html', 11, 1, 1),
(12, 'Council for the Indian School Certificate Examinations (CISCE)', 'cisce-official', 'CISCE Board', 'ALL', 'All India / Central', 'custom_html', 'EntranceAdapter', 'https://cisce.org', 'https://cisce.org/notice-board', 11, 1, 1),

-- Defense & Forces Recruitment
(13, 'Indian Army (Join Indian Army)', 'join-indian-army', 'Indian Army Recruiting Directorate', 'ALL', 'All India / Central', 'custom_html', 'DefenseAdapter', 'https://joinindianarmy.nic.in', 'https://joinindianarmy.nic.in/latest-notices.htm', 13, 1, 1),
(14, 'Indian Air Force (IAF Agniveer / AFCAT)', 'iaf-recruitment', 'Indian Air Force Recruitment Cell', 'ALL', 'All India / Central', 'custom_html', 'DefenseAdapter', 'https://afcat.cdac.in', 'https://afcat.cdac.in/AFCAT/notices', 13, 1, 1),
(15, 'Indian Navy (Join Indian Navy)', 'join-indian-navy', 'Indian Navy Recruitment Directorate', 'ALL', 'All India / Central', 'custom_html', 'DefenseAdapter', 'https://joinindiannavy.gov.in', 'https://joinindiannavy.gov.in/en/page/current-events.html', 13, 1, 1),

-- Top State Public Service Commissions & Police
(16, 'West Bengal Public Service Commission (WBPSC)', 'wbpsc-official', 'West Bengal Public Service Commission', 'WB', 'West Bengal', 'custom_html', 'StatePSCAdapter', 'https://wbpsc.gov.in', 'https://wbpsc.gov.in/whats_new.jsp', 4, 1, 1),
(17, 'West Bengal Police Recruitment Board (WBPRB)', 'wbprb-official', 'West Bengal Police Recruitment Board', 'WB', 'West Bengal', 'custom_html', 'StatePSCAdapter', 'https://prb.wb.gov.in', 'https://prb.wb.gov.in/notices', 4, 1, 1),
(18, 'Uttar Pradesh Public Service Commission (UPPSC)', 'uppsc-official', 'Uttar Pradesh Public Service Commission', 'UP', 'Uttar Pradesh', 'custom_html', 'StatePSCAdapter', 'https://uppsc.up.nic.in', 'https://uppsc.up.nic.in/AllNotifications.aspx', 4, 1, 1),
(19, 'Uttar Pradesh Subordinate Services (UPSSSC)', 'upsssc-official', 'UP Subordinate Services Selection Commission', 'UP', 'Uttar Pradesh', 'custom_html', 'StatePSCAdapter', 'https://upsssc.gov.in', 'https://upsssc.gov.in/NoticeBoard.aspx', 4, 1, 1),
(20, 'Bihar Public Service Commission (BPSC)', 'bpsc-official', 'Bihar Public Service Commission', 'BIHAR', 'Bihar', 'custom_html', 'StatePSCAdapter', 'https://bpsc.bih.nic.in', 'https://bpsc.bih.nic.in/default.htm', 4, 1, 1),
(21, 'Rajasthan Public Service Commission (RPSC)', 'rpsc-official', 'Rajasthan Public Service Commission', 'RAJ', 'Rajasthan', 'custom_html', 'StatePSCAdapter', 'https://rpsc.rajasthan.gov.in', 'https://rpsc.rajasthan.gov.in/news', 4, 1, 1),
(22, 'Madhya Pradesh Public Service Commission (MPPSC)', 'mppsc-official', 'Madhya Pradesh Public Service Commission', 'MP', 'Madhya Pradesh', 'custom_html', 'StatePSCAdapter', 'https://mppsc.mp.gov.in', 'https://mppsc.mp.gov.in/whats_new', 4, 1, 1),
(23, 'Maharashtra Public Service Commission (MPSC)', 'mpsc-official', 'Maharashtra Public Service Commission', 'MAH', 'Maharashtra', 'custom_html', 'StatePSCAdapter', 'https://mpsc.gov.in', 'https://mpsc.gov.in/recent_news', 4, 1, 1),

-- Scholarships & Higher Education
(24, 'National Scholarship Portal (NSP)', 'nsp-official', 'Ministry of Electronics and IT / Ministry of Education', 'ALL', 'All India / Central', 'custom_html', 'ScholarshipAdapter', 'https://scholarships.gov.in', 'https://scholarships.gov.in/public/schemeGuidelines', 8, 1, 1),
(25, 'University Grants Commission (UGC)', 'ugc-official', 'University Grants Commission', 'ALL', 'All India / Central', 'custom_html', 'ScholarshipAdapter', 'https://ugc.ac.in', 'https://ugc.ac.in/notices', 14, 1, 1),
(26, 'All India Council for Technical Education (AICTE)', 'aicte-official', 'AICTE India', 'ALL', 'All India / Central', 'custom_html', 'ScholarshipAdapter', 'https://aicte-india.org', 'https://aicte-india.org/bulletins/notices', 8, 1, 1);
