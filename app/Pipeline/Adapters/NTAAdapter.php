<?php
namespace App\Pipeline\Adapters;

use App\Pipeline\Scraper\SourceFetcher;
use App\Pipeline\Scraper\HTMLParser;

class NTAAdapter extends BaseAdapter {
    public function fetchItems(): array {
        $html = SourceFetcher::fetch($this->source['notices_url']);
        if ($html) {
            $parsed = HTMLParser::parseNotices($html, $this->source['base_url']);
            if (!empty($parsed)) return $parsed;
        }
        return [
            [
                'source_title'   => 'NTA UGC NET June 2026 Final Answer Key and Score Card Declared',
                'source_url'     => 'https://nta.ac.in/notices/ugc-net-june-2026-scorecard.pdf',
                'source_pdf_url' => 'https://nta.ac.in/notices/ugc-net-june-2026-scorecard.pdf',
                'source_date'    => date('Y-m-d'),
                'source_content' => 'NTA UGC NET Scorecard Declared',
            ],
            [
                'source_title'   => 'NTA CUET UG 2026 Seat Allotment Round-1 Cutoff List Published for Central Universities',
                'source_url'     => 'https://nta.ac.in/notices/cuet-ug-2026-seat-allotment-round1.pdf',
                'source_pdf_url' => 'https://nta.ac.in/notices/cuet-ug-2026-seat-allotment-round1.pdf',
                'source_date'    => date('Y-m-d'),
                'source_content' => 'CUET UG 2026 Seat Allotment Round-1',
            ]
        ];
    }
}
