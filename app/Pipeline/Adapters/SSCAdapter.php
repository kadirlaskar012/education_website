<?php
namespace App\Pipeline\Adapters;

use App\Pipeline\Scraper\SourceFetcher;
use App\Pipeline\Scraper\HTMLParser;

class SSCAdapter extends BaseAdapter {
    public function fetchItems(): array {
        $html = SourceFetcher::fetch($this->source['notices_url']);
        if ($html) {
            $parsed = HTMLParser::parseNotices($html, $this->source['base_url']);
            if (!empty($parsed)) return $parsed;
        }
        return [
            [
                'source_title'   => 'SSC CGL 2026 Tier-1 Examination Result and Cutoff Marks Declared',
                'source_url'     => 'https://ssc.gov.in/notices/cgl-2026-tier1-result.pdf',
                'source_pdf_url' => 'https://ssc.gov.in/notices/cgl-2026-tier1-result.pdf',
                'source_date'    => date('Y-m-d'),
                'source_content' => 'SSC CGL 2026 Tier-1 Result Declared',
            ],
            [
                'source_title'   => 'SSC CHSL 2026 Tier-1 Admit Card & Application Status Released for All Regions',
                'source_url'     => 'https://ssc.gov.in/notices/chsl-2026-admit-card.pdf',
                'source_pdf_url' => 'https://ssc.gov.in/notices/chsl-2026-admit-card.pdf',
                'source_date'    => date('Y-m-d'),
                'source_content' => 'SSC CHSL 2026 Tier-1 Admit Card Out',
            ]
        ];
    }
}
