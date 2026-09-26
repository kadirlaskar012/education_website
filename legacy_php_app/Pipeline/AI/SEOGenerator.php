<?php
/**
 * Advanced SEO Metadata & Schema.org JSON-LD Generator
 */

namespace App\Pipeline\AI;

class SEOGenerator {
    public static function generate(array $article, array $facts, string $categoryName, string $baseUrl): array {
        $title = trim($article['title']);
        $org = $facts['organization'] ?? 'Government Authority';

        // 1. Natural SEO Title (max 60-65 chars)
        $seoTitle = $title;
        if (mb_strlen($seoTitle) > 65) {
            $seoTitle = mb_substr($title, 0, 60) . '...';
        }

        // 2. Natural Meta Description (140-160 chars)
        $metaDescription = $article['excerpt'] ?? '';
        if (empty($metaDescription) || mb_strlen($metaDescription) < 50) {
            $metaDescription = "{$title} — Official notice released by {$org}. Check verified dates, guidelines, steps, and direct download links.";
        }
        if (mb_strlen($metaDescription) > 160) {
            $metaDescription = mb_substr($metaDescription, 0, 157) . '...';
        }

        // 3. Clean URL Slug
        $slug = self::generateSlug($title);

        // 4. Schema.org JSON-LD (NewsArticle + BreadcrumbList + FAQPage)
        $canonicalUrl = rtrim($baseUrl, '/') . '/news/' . $slug;
        $publishedTime = date('c', strtotime($article['published_at'] ?? 'now'));
        $updatedTime = date('c', strtotime($article['updated_at'] ?? 'now'));

        $schemaArray = [
            '@context' => 'https://schema.org',
            '@graph'   => [
                [
                    '@type'            => 'NewsArticle',
                    '@id'              => $canonicalUrl . '#article',
                    'isPartOf'         => ['@id' => $canonicalUrl],
                    'headline'         => $title,
                    'description'      => $metaDescription,
                    'datePublished'    => $publishedTime,
                    'dateModified'     => $updatedTime,
                    'mainEntityOfPage' => $canonicalUrl,
                    'publisher'        => [
                        '@type' => 'Organization',
                        'name'  => 'EduGov News',
                        'url'   => $baseUrl,
                    ],
                ],
                [
                    '@type'           => 'BreadcrumbList',
                    '@id'             => $canonicalUrl . '#breadcrumb',
                    'itemListElement' => [
                        [
                            '@type'    => 'ListItem',
                            'position' => 1,
                            'name'     => 'Home',
                            'item'     => $baseUrl . '/',
                        ],
                        [
                            '@type'    => 'ListItem',
                            'position' => 2,
                            'name'     => $categoryName,
                            'item'     => $baseUrl . '/category/' . ($facts['category_slug'] ?? 'latest-news'),
                        ],
                        [
                            '@type'    => 'ListItem',
                            'position' => 3,
                            'name'     => $title,
                            'item'     => $canonicalUrl,
                        ]
                    ]
                ]
            ]
        ];

        // If FAQs exist, add FAQPage schema
        if (!empty($facts['faqs']) && is_array($facts['faqs'])) {
            $faqElements = [];
            foreach ($facts['faqs'] as $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    $faqElements[] = [
                        '@type'          => 'Question',
                        'name'           => $faq['question'],
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text'  => $faq['answer'],
                        ]
                    ];
                }
            }
            if (!empty($faqElements)) {
                $schemaArray['@graph'][] = [
                    '@type'      => 'FAQPage',
                    '@id'        => $canonicalUrl . '#faq',
                    'mainEntity' => $faqElements,
                ];
            }
        }

        return [
            'seo_title'        => $seoTitle,
            'meta_description' => $metaDescription,
            'slug'             => $slug,
            'canonical_url'    => $canonicalUrl,
            'schema_json'      => json_encode($schemaArray, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ];
    }

    public static function generateSlug(string $title): string {
        $slug = preg_replace('~[^\pL\d]+~u', '-', $title);
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
        $slug = preg_replace('~[^-\w]+~', '', $slug);
        $slug = trim($slug, '-');
        $slug = preg_replace('~-+~', '-', $slug);
        $slug = strtolower($slug);
        return $slug ?: 'notification-' . time();
    }
}
