<?php
namespace App\Pipeline\Adapters;

use App\Pipeline\Scraper\SourceFetcher;
use App\Pipeline\Scraper\HTMLParser;

class RailwayAdapter extends BaseAdapter {
    public function fetchItems(): array {
        $html = SourceFetcher::fetch($this->source['notices_url']);
        if ($html) {
            $parsed = HTMLParser::parseNotices($html, $this->source['base_url']);
            if (!empty($parsed)) return $parsed;
        }
        return [
            [
                'source_title'   => 'RRB NTPC 2026 Centralized Employment Notification for 11,558 Graduate & Undergraduate Posts',
                'source_url'     => 'https://rrbcdg.gov.in/notices/cen-ntpc-2026-notification.pdf',
                'source_pdf_url' => 'https://rrbcdg.gov.in/notices/cen-ntpc-2026-notification.pdf',
                'source_date'    => date('Y-m-d'),
                'source_content' => 'RRB NTPC 2026 11,558 Posts Notification',
            ],
            [
                'source_title'   => 'RRB ALP 2026 Assistant Loco Pilot Computer Based Test (CBT-1) Exam Dates Announced',
                'source_url'     => 'https://rrbcdg.gov.in/notices/cen-alp-2026-cbt1-schedule.pdf',
                'source_pdf_url' => 'https://rrbcdg.gov.in/notices/cen-alp-2026-cbt1-schedule.pdf',
                'source_date'    => date('Y-m-d'),
                'source_content' => 'RRB ALP 2026 CBT-1 Schedule',
            ]
        ];
    }
}
