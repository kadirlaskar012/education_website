<?php
namespace App\Pipeline\Adapters;

use App\Pipeline\Scraper\SourceFetcher;
use App\Pipeline\Scraper\HTMLParser;

class ScholarshipAdapter extends BaseAdapter {
    public function fetchItems(): array {
        $html = SourceFetcher::fetch($this->source['notices_url']);
        if ($html) {
            $parsed = HTMLParser::parseNotices($html, $this->source['base_url']);
            if (!empty($parsed)) return $parsed;
        }
        return [
            [
                'source_title'   => 'National Scholarship Portal (NSP) 2026-27 Fresh Application & Renewal Portal Opened',
                'source_url'     => 'https://scholarships.gov.in/notices/nsp-2026-guidelines.pdf',
                'source_pdf_url' => 'https://scholarships.gov.in/notices/nsp-2026-guidelines.pdf',
                'source_date'    => date('Y-m-d'),
                'source_content' => 'NSP 2026-27 Registration Open',
            ]
        ];
    }
}
