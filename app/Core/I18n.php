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
