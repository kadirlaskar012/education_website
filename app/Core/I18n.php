<?php
/**
 * Internationalization (I18n) & Localization Engine
 * Supports: English (en), Bengali (bn), Hindi (hi)
 */

namespace App\Core {

class I18n {
    private static string $locale = 'en';
    private static array $translations = [];

    public static function init(): void {
        if (session_status() === PHP_SESSION_NONE) {
            @session_start();
        }

        // 1. Check Query Param (?lang=bn)
        if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'bn', 'hi'])) {
            self::setLocale($_GET['lang']);
            return;
        }

        // 2. Check Session
        if (isset($_SESSION['app_lang']) && in_array($_SESSION['app_lang'], ['en', 'bn', 'hi'])) {
            self::$locale = $_SESSION['app_lang'];
            return;
        }

        // 3. Check Cookie
        if (isset($_COOKIE['app_lang']) && in_array($_COOKIE['app_lang'], ['en', 'bn', 'hi'])) {
            self::$locale = $_COOKIE['app_lang'];
            $_SESSION['app_lang'] = self::$locale;
            return;
        }

        // 4. Default
        self::$locale = 'en';
    }

    public static function setLocale(string $lang): void {
        if (in_array($lang, ['en', 'bn', 'hi'])) {
            self::$locale = $lang;
            if (session_status() === PHP_SESSION_NONE) {
                @session_start();
            }
            $_SESSION['app_lang'] = $lang;
            @setcookie('app_lang', $lang, time() + (86400 * 90), '/'); // 90 days cookie
        }
    }

    public static function getLocale(): string {
        return self::$locale;
    }

    public static function getLanguages(): array {
        return [
            'en' => ['name' => 'English', 'native' => 'English', 'flag' => '🇬🇧'],
            'bn' => ['name' => 'Bengali', 'native' => 'বাংলা', 'flag' => '🇧🇩'],
            'hi' => ['name' => 'Hindi', 'native' => 'हिंदी', 'flag' => '🇮🇳'],
        ];
    }

    public static function trans(string $key, array $replace = [], ?string $locale = null): string {
        $locale = $locale ?: self::$locale;
        $strings = self::loadDictionary($locale);

        $translation = $strings[$key] ?? (self::loadDictionary('en')[$key] ?? $key);

        foreach ($replace as $placeholder => $value) {
            $translation = str_replace(':' . $placeholder, $value, $translation);
        }

        return $translation;
    }

    private static function loadDictionary(string $locale): array {
        if (isset(self::$translations[$locale])) {
            return self::$translations[$locale];
        }

        $dicts = [
            'en' => [
                'site_name' => 'EduGov News',
                'site_tagline' => 'Official Education Updates, Exam Dates, Admit Cards & Results',
                'nav_home' => 'Home',
                'nav_results' => 'Results',
                'nav_admit_card' => 'Admit Card',
                'nav_recruitment' => 'Recruitment',
                'nav_exam_dates' => 'Exam Dates',
                'nav_answer_key' => 'Answer Key',
                'nav_scholarship' => 'Scholarships',
                'nav_categories' => 'Categories',
                'nav_states' => 'State Portals',
                'search_placeholder' => 'Search exam dates, recruitments, admit cards...',
                'breaking_label' => 'BREAKING NEWS',
                'latest_updates' => 'Latest Official Notifications',
                'verified_badge' => 'VERIFIED OFFICIAL',
                'read_more' => 'Read Full Notice',
                'share' => 'Share Update',
                'download_pdf' => 'Download Official PDF',
                'official_website' => 'Official Authority Portal',
                'apply_online' => 'Apply Online Now',
                'telegram_join_title' => 'Join 50,000+ Students on Telegram',
                'telegram_join_sub' => 'Get instant exam alerts & PDF notices directly on your phone.',
                'telegram_btn' => 'Join Telegram Channel Free',
                'sidebar_trending' => '🔥 Most Viewed Updates',
                'sidebar_recent' => '⚡ Recent Notices',
                'read_in_language' => 'Read this article in:',
                'footer_disclaimer' => 'EduGov News is a dedicated educational news indexing portal. All updates are curated from verified government websites.',
                'rights_reserved' => 'All Rights Reserved.',
                'language' => 'Language',
                'top_announcement' => 'TOP ANNOUNCEMENT',
                'trending_live_notices' => 'Trending Live Notices',
                'live_updates' => 'LIVE UPDATES',
                'browse_all_notices' => 'Browse All Active Government Notices »',
                'community_bar_title' => 'Get Instant Exam & Job Alerts on Mobile!',
                'community_bar_sub' => 'Join 100,000+ candidates receiving verified official notifications in 1 click.',
                'join_telegram' => '✈️ Join Telegram Channel',
                'join_whatsapp' => '💬 Join WhatsApp Group',
                'state_matrix_title' => 'State-Wise & Central Portals',
                'state_matrix_sub' => 'Direct access to state public service commissions',
                'all_eligible' => '🎓 All Eligible',
                'direct_apply_active' => '⚡ Direct Apply Active',
                'read_full_notification' => 'Read Full Notification',
                'results_section_title' => 'Results & Merit Lists',
                'view_all_results' => 'View All Results →',
                'check_score_merit' => 'Check Score & Merit List',
                'admit_cards_section_title' => 'Admit Cards & Exam City Slips',
                'view_all_admits' => 'View All Admit Cards →',
                'download_admit_btn' => 'Download Admit Card',
                'jobs_section_title' => 'Government Recruitment & Vacancies',
                'view_all_jobs' => 'View All Recruitment →',
                'read_eligibility_apply' => 'Read Eligibility & Apply',
                'smart_filter_title' => 'Instant Smart Notice Filter',
                'showing_all_updates' => 'Showing All Updates',
                'clear_filter' => '✕ Clear Filter',
                'qualification_label' => '🎓 Qualification:',
                'department_label' => '🏛️ Department:',
                'notice_type_label' => '📋 Notice Type:',
                'qual_all' => 'All',
                'qual_10th' => '10th / Matric',
                'qual_12th' => '12th / HS',
                'qual_graduate' => 'Graduate / Degree',
                'qual_diploma' => 'Diploma / ITI',
                'qual_pg' => 'Post Graduate',
                'sec_railway' => '🚆 Railway (RRB)',
                'sec_ssc' => '🏛️ SSC & Central',
                'sec_police' => '👮 Police & Defense',
                'sec_banking' => '🏦 Banking & IBPS',
                'sec_wbpsc' => '🌊 West Bengal',
                'sec_upsc' => '⚖️ UPSC & Civil',
                'type_vacancies' => '💼 New Vacancies',
                'type_admits' => '🎟️ Admit Cards',
                'type_results' => '🏆 Results & Merit',
                'type_answer_keys' => '📝 Answer Keys',
                'conducting_authority' => 'Conducting Authority',
                'notification_type' => 'Notification Type',
                'release_date' => 'Release Date',
                'official_portal_link' => 'Official Portal Link',
                'key_highlights_title' => 'Key Highlights & Official Summary Factsheet',
                'verified_source_heading' => 'Official Government Source Verification',
                'verified_source_desc' => 'This announcement is automatically synchronized and verified from the public notification issued by :authority.',
                'direct_source' => 'Direct Official Source:',
                'official_pdf_doc' => 'Official PDF Document:',
                'views_count' => 'views',
                'min_read' => 'min read',
                'words' => 'words',
                'updated' => 'Updated',
                'listen_audio' => '🎧 Listen to this Official Update (Audio Narration)',
                'audio_meta' => 'min listen • Clear Voice Summary',
                'copy_link' => '📋 Copy Link',
                'print_notice' => '🖨️ Print Notice',
                'related_notices' => 'Related Official Notifications',
                'recent_notices' => 'Recent Notices',
                'no_notices_found' => 'No notices found for this selection',
                'all_regions' => 'All Regions',
                'change_state' => 'Change State / Region',
                'select_state' => 'Select State / Region',
                'active_filter' => 'Active Filter:',
                'showing_all_regions' => 'Showing: All Regions',
                'select_state_region' => '📌 Select State / Region:',
                'quick_categories' => '📂 Quick Categories',
                'official_authenticity' => 'Official Authenticity',
                'official_auth_desc' => 'All articles on EduGov News are strictly synchronized from verified government notifications. Candidates are always provided direct links to official .gov.in and .nic.in portals.',
                'search_results_for' => 'Search Results For:',
                'popular_tags' => 'Popular Tags:',
            ],
            'bn' => [
                'site_name' => 'এডুগভ নিউজ',
                'site_tagline' => 'অফিসিয়াল শিক্ষা আপডেট, পরীক্ষার তারিখ, অ্যাডমিট কার্ড ও রেজাল্ট',
                'nav_home' => 'হোম',
                'nav_results' => 'রেজাল্ট',
                'nav_admit_card' => 'অ্যাডমিট কার্ড',
                'nav_recruitment' => 'চাকরি ও নিয়োগ',
                'nav_exam_dates' => 'পরীক্ষার দিনক্ষণ',
                'nav_answer_key' => 'উত্তর সংকেত (Answer Key)',
                'nav_scholarship' => 'স্কলারশিপ',
                'nav_categories' => 'বিভাগসমূহ',
                'nav_states' => 'রাজ্যভিত্তিক পোর্টাল',
                'search_placeholder' => 'পরীক্ষার তারিখ, চাকরি, অ্যাডমিট কার্ড খুঁজুন...',
                'breaking_label' => 'জরুরি খবর',
                'latest_updates' => 'সর্বশেষ অফিসিয়াল বিজ্ঞপ্তি',
                'verified_badge' => 'যাচাইকৃত অফিসিয়াল',
                'read_more' => 'সম্পূর্ণ বিজ্ঞপ্তি পড়ুন',
                'share' => 'শেয়ার করুন',
                'download_pdf' => 'অফিসিয়াল PDF ডাউনলোড',
                'official_website' => 'অফিসিয়াল পোর্টাল দেখুন',
                'apply_online' => 'অনলাইনে আবেদন করুন',
                'telegram_join_title' => '৫০,০০০+ ছাত্রছাত্রীদের সাথে টেলিগ্রামে যুক্ত হন',
                'telegram_join_sub' => 'পরীক্ষার আপডেট ও অফিশিয়াল নোটিশ সরাসরি আপনার মোবাইলে পান।',
                'telegram_btn' => 'টেলিগ্রাম চ্যানেলে যুক্ত হন (ফ্রি)',
                'sidebar_trending' => '🔥 জনপ্রিয় ও ট্রেন্ডিং খবর',
                'sidebar_recent' => '⚡ সাম্প্রতিক বিজ্ঞপ্তি',
                'read_in_language' => 'এই খবরটি পড়ুন:',
                'footer_disclaimer' => 'এডুগভ নিউজ একটি স্বাধীন শিক্ষা সংবাদ ও নোটিফিকেশন পোর্টাল। সমস্ত তথ্য সরকারি ওয়েবসাইট থেকে যাচাই করে প্রকাশ করা হয়।',
                'rights_reserved' => 'সর্বস্বত্ব সংরক্ষিত।',
                'language' => 'ভাষা',
                'top_announcement' => 'শীর্ষ ঘোষণা',
                'trending_live_notices' => 'ট্রেন্ডিং লাইভ বিজ্ঞপ্তি',
                'live_updates' => 'লাইভ আপডেট',
                'browse_all_notices' => 'সমস্ত সরকারি বিজ্ঞপ্তি দেখুন »',
                'community_bar_title' => 'মোবাইলে পান পরীক্ষা ও চাকরির তাৎক্ষণিক আপডেট!',
                'community_bar_sub' => '১ ক্লিকেই ১ লক্ষেরও বেশি প্রার্থীর সাথে সরকারি বিজ্ঞপ্তির আপডেট পান।',
                'join_telegram' => '✈️ টেলিগ্রাম চ্যানেলে যুক্ত হন',
                'join_whatsapp' => '💬 হোয়াটসঅ্যাপ গ্রুপে যুক্ত হন',
                'state_matrix_title' => 'রাজ্যভিত্তিক ও কেন্দ্রীয় পোর্টাল',
                'state_matrix_sub' => 'রাজ্য পাবলিক সার্ভিস কমিশন ও কেন্দ্রীয় পোর্টাল লিঙ্ক',
                'all_eligible' => '🎓 সকল যোগ্য প্রার্থী',
                'direct_apply_active' => '⚡ সরাসরি আবেদন চলছে',
                'read_full_notification' => 'সম্পূর্ণ বিজ্ঞপ্তি পড়ুন',
                'results_section_title' => 'রেজাল্ট ও মেধা তালিকা',
                'view_all_results' => 'সমস্ত রেজাল্ট দেখুন →',
                'check_score_merit' => 'স্কোর ও মেধা তালিকা দেখুন',
                'admit_cards_section_title' => 'অ্যাডমিট কার্ড ও পরীক্ষার সেন্টার স্লিপ',
                'view_all_admits' => 'সমস্ত অ্যাডমিট কার্ড দেখুন →',
                'download_admit_btn' => 'অ্যাডমিট কার্ড ডাউনলোড',
                'jobs_section_title' => 'সরকারি চাকরি ও নতুন নিয়োগ',
                'view_all_jobs' => 'সমস্ত নিয়োগ বিজ্ঞপ্তি দেখুন →',
                'read_eligibility_apply' => 'যোগ্যতা যাচাই ও আবেদন',
                'smart_filter_title' => 'তাৎক্ষণিক স্মার্ট নোটিশ ফিল্টার',
                'showing_all_updates' => 'সমস্ত আপডেট দেখানো হচ্ছে',
                'clear_filter' => '✕ ফিল্টার মুছুন',
                'qualification_label' => '🎓 শিক্ষাগত যোগ্যতা:',
                'department_label' => '🏛️ দপ্তর বা বোর্ড:',
                'notice_type_label' => '📋 বিজ্ঞপ্তির ধরন:',
                'qual_all' => 'সকল',
                'qual_10th' => 'দশম শ্রেণী / মাধ্যমিক',
                'qual_12th' => 'দ্বাদশ শ্রেণী / উচ্চমাধ্যমিক',
                'qual_graduate' => 'স্নাতক / ডিগ্রি',
                'qual_diploma' => 'ডিপ্লোমা / আইটিআই',
                'qual_pg' => 'স্নাতকোত্তর (PG)',
                'sec_railway' => '🚆 রেলওয়ে রিক্রুটমেন্ট (RRB)',
                'sec_ssc' => '🏛️ এসএসসি ও কেন্দ্রীয়',
                'sec_police' => '👮 পুলিশ ও প্রতিরক্ষা',
                'sec_banking' => '🏦 ব্যাংকিং ও আইবিপিএস',
                'sec_wbpsc' => '🌊 পশ্চিমবঙ্গ',
                'sec_upsc' => '⚖️ ইউপিএসসি ও সিভিল',
                'type_vacancies' => '💼 নতুন চাকরি',
                'type_admits' => '🎟️ অ্যাডমিট কার্ড',
                'type_results' => '🏆 ফলাফল ও রেজাল্ট',
                'type_answer_keys' => '📝 উত্তর সংকেত',
                'conducting_authority' => 'পরিচালনাকারী কর্তৃপক্ষ',
                'notification_type' => 'বিজ্ঞপ্তির বিষয়',
                'release_date' => 'প্রকাশের তারিখ',
                'official_portal_link' => 'অফিসিয়াল পোর্টাল লিঙ্ক',
                'key_highlights_title' => 'গুরুত্বপূর্ণ তথ্য ও অফিসিয়াল তথ্যের বিবরণ',
                'verified_source_heading' => 'সরকারি অফিসিয়াল সূত্রের সত্যতা যাচাই',
                'verified_source_desc' => 'এই বিজ্ঞপ্তিটি :authority কর্তৃক প্রকাশিত অফিশিয়াল বিজ্ঞপ্তি থেকে স্বয়ংক্রিয়ভাবে যাচাই করা হয়েছে।',
                'direct_source' => 'সরাসরি অফিশিয়াল উৎস:',
                'official_pdf_doc' => 'অফিসিয়াল PDF বিজ্ঞপ্তি:',
                'views_count' => 'বার পড়া হয়েছে',
                'min_read' => 'মিনিট পাঠ',
                'words' => 'শব্দ',
                'updated' => 'সংশোধিত',
                'listen_audio' => '🎧 এই বিজ্ঞপ্তিটির অডিও সারসংক্ষেপ শুনুন',
                'audio_meta' => 'মিনিট শুনুন • স্পষ্ট বাংলা ভয়েস সারাংশ',
                'copy_link' => '📋 লিংক কপি করুন',
                'print_notice' => '🖨️ নোটিশ প্রিন্ট করুন',
                'related_notices' => 'সম্পর্কিত অন্যান্য অফিসিয়াল বিজ্ঞপ্তি',
                'recent_notices' => 'সাম্প্রতিক বিজ্ঞপ্তি',
                'no_notices_found' => 'এই বিভাগে এই মুহূর্তে কোনো বিজ্ঞপ্তি পাওয়া যায়নি',
                'all_regions' => 'সমস্ত রাজ্য ও অঞ্চল',
                'change_state' => 'রাজ্য বা অঞ্চল পরিবর্তন করুন',
                'select_state' => 'রাজ্য বা অঞ্চল বাছুন',
                'active_filter' => 'বর্তমান ফিল্টার:',
                'showing_all_regions' => 'সমস্ত অঞ্চল প্রদর্শিত হচ্ছে',
                'select_state_region' => '📌 রাজ্য বা অঞ্চল বেছে নিন:',
                'quick_categories' => '📂 দ্রুত বিভাগসমূহ',
                'official_authenticity' => 'অফিসিয়াল সত্যতা ও বিশ্বাসযোগ্যতা',
                'official_auth_desc' => 'এডুগভ নিউজের প্রতিটি সংবাদ সরকারি ওয়েবসাইট থেকে নিখুঁতভাবে যাচাই করে প্রকাশিত হয়। প্রার্থীদের জন্য সরাসরি .gov.in এবং .nic.in পোর্টালের লিঙ্ক প্রদান করা হয়।',
                'search_results_for' => 'অনুসন্ধানের ফলাফল:',
                'popular_tags' => 'জনপ্রিয় ট্যাগ:',
            ],
            'hi' => [
                'site_name' => 'एडूगव न्यूज़',
                'site_tagline' => 'आधिकारिक शिक्षा समाचार, परीक्षा तिथियां, एडमिट कार्ड और परिणाम',
                'nav_home' => 'होम',
                'nav_results' => 'रिजल्ट',
                'nav_admit_card' => 'एडमिट कार्ड',
                'nav_recruitment' => 'भर्ती एवं सरकारी नौकरी',
                'nav_exam_dates' => 'परीक्षा तिथियां',
                'nav_answer_key' => 'उत्तर कुंजी (Answer Key)',
                'nav_scholarship' => 'छात्रवृत्ति',
                'nav_categories' => 'श्रेणियां',
                'nav_states' => 'राज्यवार पोर्टल',
                'search_placeholder' => 'परीक्षा तिथि, एडमिट कार्ड, भर्ती खोजें...',
                'breaking_label' => 'ब्रेकिंग न्यूज़',
                'latest_updates' => 'नवीनतम आधिकारिक सूचनाएं',
                'verified_badge' => 'सत्यापित आधिकारिक',
                'read_more' => 'पूरी सूचना पढ़ें',
                'share' => 'शेयर करें',
                'download_pdf' => 'आधिकारिक PDF डाउनलोड',
                'official_website' => 'आधिकारिक पोर्टल पर जाएं',
                'apply_online' => 'ऑनलाइन आवेदन करें',
                'telegram_join_title' => '50,000+ छात्रों के साथ टेलीग्राम से जुड़ें',
                'telegram_join_sub' => 'परीक्षा अलर्ट और आधिकारिक नोटिस सीधे अपने फोन पर पाएं।',
                'telegram_btn' => 'टेलीग्राम चैनल से जुड़ें (मुफ़्त)',
                'sidebar_trending' => '🔥 सर्वाधिक पढ़े गए समाचार',
                'sidebar_recent' => '⚡ हाल की सूचनाएं',
                'read_in_language' => 'यह समाचार पढ़ें:',
                'footer_disclaimer' => 'एडूगव न्यूज़ एक स्वतंत्र शैक्षिक समाचार पोर्टल है। सभी सूचनाएं आधिकारिक सरकारी वेबसाइटों से सत्यापित की जाती हैं।',
                'rights_reserved' => 'सर्वाधिकार सुरक्षित।',
                'language' => 'भाषा',
                'top_announcement' => 'शीर्ष घोषणा',
                'trending_live_notices' => 'ट्रेंडिंग लाइव सूचनाएं',
                'live_updates' => 'लाइव अपडेट',
                'browse_all_notices' => 'सभी सक्रिय सरकारी सूचनाएं देखें »',
                'community_bar_title' => 'मोबाइल पर पाएं परीक्षा और नौकरी अलर्ट!',
                'community_bar_sub' => '1 क्लिक में 1 लाख से अधिक उम्मीदवारों के साथ आधिकारिक अपडेट पाएं।',
                'join_telegram' => '✈️ टेलीग्राम चैनल से जुड़ें',
                'join_whatsapp' => '💬 व्हाट्सएप ग्रुप से जुड़ें',
                'state_matrix_title' => 'राज्यवार एवं केंद्रीय पोर्टल',
                'state_matrix_sub' => 'राज्य लोक सेवा आयोगों का सीधा पोर्टल',
                'all_eligible' => '🎓 सभी पात्र उम्मीदवार',
                'direct_apply_active' => '⚡ सीधा आवेदन सक्रिय',
                'read_full_notification' => 'पूरी अधिसूचना पढ़ें',
                'results_section_title' => 'परीक्षा परिणाम एवं मेरिट सूची',
                'view_all_results' => 'सभी परिणाम देखें →',
                'check_score_merit' => 'स्कोर व मेरिट सूची देखें',
                'admit_cards_section_title' => 'एडमिट कार्ड और परीक्षा सिटी स्लिप',
                'view_all_admits' => 'सभी एडमिट कार्ड देखें →',
                'download_admit_btn' => 'एडमिट कार्ड डाउनलोड करें',
                'jobs_section_title' => 'सरकारी भर्ती एवं रिक्तियां',
                'view_all_jobs' => 'सभी भर्ती सूचनाएं देखें →',
                'read_eligibility_apply' => 'पात्रता जांचें व आवेदन करें',
                'smart_filter_title' => 'त्वरित स्मार्ट नोटिस फिल्टर',
                'showing_all_updates' => 'सभी अपडेट प्रदर्शित',
                'clear_filter' => '✕ फिल्टर हटाएं',
                'qualification_label' => '🎓 शैक्षणिक योग्यता:',
                'department_label' => '🏛️ विभाग / आयोग:',
                'notice_type_label' => '📋 नोटिस का प्रकार:',
                'qual_all' => 'सभी',
                'qual_10th' => '10वीं / मैट्रिक',
                'qual_12th' => '12वीं / इंटर',
                'qual_graduate' => 'स्नातक / डिग्री',
                'qual_diploma' => 'डिप्लोमा / आईटीआई',
                'qual_pg' => 'स्नातकोत्तर (PG)',
                'sec_railway' => '🚆 रेलवे (RRB)',
                'sec_ssc' => '🏛️ एसएससी व केंद्रीय',
                'sec_police' => '👮 पुलिस एवं रक्षा',
                'sec_banking' => '🏦 बैंकिंग एवं आईबीपीएस',
                'sec_wbpsc' => '🌊 पश्चिम बंगाल',
                'sec_upsc' => '⚖️ यूपीएससी एवं सिविल',
                'type_vacancies' => '💼 नई रिक्तियां',
                'type_admits' => '🎟️ एडमिट कार्ड',
                'type_results' => '🏆 परिणाम व मेरिट',
                'type_answer_keys' => '📝 उत्तर कुंजी',
                'conducting_authority' => 'परीक्षा प्राधिकरण',
                'notification_type' => 'अधिसूचना प्रकार',
                'release_date' => 'जारी होने की तिथि',
                'official_portal_link' => 'आधिकारिक पोर्टल लिंक',
                'key_highlights_title' => 'मुख्य बिंदु एवं आधिकारिक सारांश',
                'verified_source_heading' => 'आधिकारिक सरकारी स्रोत सत्यापन',
                'verified_source_desc' => 'यह सूचना :authority द्वारा जारी सार्वजनिक अधिसूचना से स्वचालित रूप से सत्यापित है।',
                'direct_source' => 'सीधा आधिकारिक स्रोत:',
                'official_pdf_doc' => 'आधिकारिक PDF दस्तावेज:',
                'views_count' => 'बार देखा गया',
                'min_read' => 'मिनट पठन',
                'words' => 'शब्द',
                'updated' => 'अपडेटेड',
                'listen_audio' => '🎧 इस आधिकारिक सूचना का ऑडियो सारांश सुनें',
                'audio_meta' => 'मिनट सुनें • स्पष्ट हिंदी ऑडियो सारांश',
                'copy_link' => '📋 लिंक कॉपी करें',
                'print_notice' => '🖨️ प्रिंट निकालें',
                'related_notices' => 'संबंधित आधिकारिक सूचनाएं',
                'recent_notices' => 'हाल की सूचनाएं',
                'no_notices_found' => 'इस चयन के लिए कोई सूचना नहीं मिली',
                'all_regions' => 'सभी क्षेत्र',
                'change_state' => 'राज्य या क्षेत्र बदलें',
                'select_state' => 'राज्य या क्षेत्र चुनें',
                'active_filter' => 'सक्रिय फिल्टर:',
                'showing_all_regions' => 'सभी क्षेत्र दिखाए जा रहे हैं',
                'select_state_region' => '📌 राज्य या क्षेत्र चुनें:',
                'quick_categories' => '📂 त्वरित श्रेणियां',
                'official_authenticity' => 'आधिकारिक प्रामाणिकता',
                'official_auth_desc' => 'एडूगव न्यूज़ पर सभी समाचार सरकारी वेबसाइटों से सत्यापित करके प्रकाशित किए जाते हैं। उम्मीदवारों को सीधे .gov.in और .nic.in के आधिकारिक लिंक दिए जाते हैं।',
                'search_results_for' => 'खोज परिणाम:',
                'popular_tags' => 'लोकप्रिय टैग:',
            ]
        ];

        self::$translations = $dicts;
        return $dicts[$locale] ?? $dicts['en'];
    }
}
}

namespace {
    // Global helper function for concise view templating
    if (!function_exists('__')) {
        function __(string $key, array $replace = []): string {
            return \App\Core\I18n::trans($key, $replace);
        }
    }
}
