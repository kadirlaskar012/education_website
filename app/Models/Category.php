<?php
/**
 * Category Model
 */

namespace App\Models;

class Category {
    private \PDO $db;

    public function __construct() {
        $this->db = \Database::getConnection();
    }

    public static function localize(?array $category, ?string $locale = null): ?array {
        if (!$category) return null;
        $locale = $locale ?: \App\Core\I18n::getLocale();
        if ($locale === 'en') return $category;

        $slug = $category['slug'] ?? '';
        $map = [
            'bn' => [
                'results' => ['name' => 'রেজাল্ট ও ফলাফল', 'description' => 'সরকারি ও বোর্ড পরীক্ষার সর্বশেষ রেজাল্ট ও মেধা তালিকা।'],
                'admit-card' => ['name' => 'অ্যাডমিট কার্ড', 'description' => 'পরীক্ষার প্রবেশপত্র ও হল টিকিট ডাউনলোড লিঙ্ক।'],
                'recruitment' => ['name' => 'সরকারি চাকরি ও নিয়োগ', 'description' => 'কেন্দ্র ও রাজ্য সরকারি শূন্যপদে নতুন চাকরির বিজ্ঞপ্তি ও আবেদন।'],
                'exam' => ['name' => 'পরীক্ষার দিনক্ষণ ও সূচি', 'description' => 'আসন্ন সরকারি ও প্রতিযোগিতামূলক পরীক্ষার সময়সূচি।'],
                'answer-key' => ['name' => 'উত্তর সংকেত (Answer Key)', 'description' => 'অফিসিয়াল অ্যানসার কি ও ওএমআর শিট চ্যালেঞ্জ সংক্রান্ত তথ্য।'],
                'scholarship' => ['name' => 'স্কলারশিপ ও অনুদান', 'description' => 'জাতীয় ও রাজ্যভিত্তিক ছাত্রবৃত্তি এবং আর্থিক সহায়তার পোর্টাল।'],
                'admission' => ['name' => 'ভর্তি ও কাউন্সেলিং', 'description' => 'বিশ্ববিদ্যালয় ও কলেজ স্তরে ভর্তি প্রক্রিয়া ও কাউন্সেলিং আপডেট।'],
                'board-exams' => ['name' => 'বোর্ড পরীক্ষা (Madhyamik/HS)', 'description' => 'মাধ্যমিক, উচ্চমাধ্যমিক ও কেন্দ্রীয় বোর্ড পরীক্ষার যাবতীয় তথ্য।'],
                'syllabus' => ['name' => 'পরীক্ষার সিলেবাস ও প্যাটার্ন', 'description' => 'সরকারি পরীক্ষার নম্বর বিভাজন ও বিস্তারিত সিলেবাস।'],
                'cutoff' => ['name' => 'কাট-অফ মার্কস', 'description' => 'ক্যাটাগরিভিত্তিক পূর্ববর্তী ও সম্ভাব্য কাট-অফ নম্বর।'],
            ],
            'hi' => [
                'results' => ['name' => 'रिजल्ट एवं परिणाम', 'description' => 'सरकारी एवं बोर्ड परीक्षाओं के आधिकारिक परिणाम व मेरिट सूची।'],
                'admit-card' => ['name' => 'एडमिट कार्ड एवं हॉल टिकट', 'description' => 'परीक्षा प्रवेश पत्र एवं परीक्षा केंद्र आवंटन सूची।'],
                'recruitment' => ['name' => 'सरकारी भर्ती एवं नौकरी', 'description' => 'केंद्र एवं राज्य सरकार की नवीनतम रिक्तियां और ऑनलाइन आवेदन।'],
                'exam' => ['name' => 'परीक्षा तिथियां एवं कार्यक्रम', 'description' => 'आगामी प्रतियोगी एवं बोर्ड परीक्षाओं की आधिकारिक तिथियां।'],
                'answer-key' => ['name' => 'उत्तर कुंजी (Answer Key)', 'description' => 'आधिकारिक उत्तर कुंजी एवं आपत्ति दर्ज करने की प्रक्रिया।'],
                'scholarship' => ['name' => 'छात्रवृत्ति योजनाएं', 'description' => 'राष्ट्रीय एवं राज्य स्तरीय छात्रवृत्ति आवेदन विवरण।'],
                'admission' => ['name' => 'प्रवेश एवं काउंसलिंग', 'description' => 'कॉलेज एवं विश्वविद्यालय प्रवेश सूचनाएं।'],
                'board-exams' => ['name' => 'बोर्ड परीक्षाएं (CBSE/State)', 'description' => '10वीं व 12वीं बोर्ड परीक्षाओं के आधिकारिक अपडेट।'],
                'syllabus' => ['name' => 'परीक्षा पाठ्यक्रम (Syllabus)', 'description' => 'परीक्षा पैटर्न एवं संपूर्ण सिलेबस विवरण।'],
                'cutoff' => ['name' => 'कट-ऑफ अंक', 'description' => 'श्रेणीवार आधिकारिक एवं अपेक्षित कट-ऑफ अंक।'],
            ]
        ];

        if (isset($map[$locale][$slug])) {
            $category['name'] = $map[$locale][$slug]['name'];
            if (!empty($category['description'])) {
                $category['description'] = $map[$locale][$slug]['description'];
            }
        }

        return $category;
    }

    public static function localizeList(array $categories, ?string $locale = null): array {
        return array_map(fn($c) => self::localize($c, $locale), $categories);
    }

    public function getActiveCategories(): array {
        $stmt = $this->db->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY display_order ASC, name ASC");
        return self::localizeList($stmt->fetchAll());
    }

    public function findBySlug(string $slug): ?array {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE slug = :slug AND is_active = 1 LIMIT 1");
        $stmt->execute([':slug' => $slug]);
        $cat = $stmt->fetch();
        return $cat ? self::localize($cat) : null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $cat = $stmt->fetch();
        return $cat ? self::localize($cat) : null;
    }
}
