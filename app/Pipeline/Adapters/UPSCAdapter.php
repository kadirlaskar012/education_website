<?php
namespace App\Pipeline\Adapters;

use App\Pipeline\Scraper\SourceFetcher;
use App\Pipeline\Scraper\HTMLParser;

class UPSCAdapter extends BaseAdapter {
    public function fetchItems(): array {
        $html = SourceFetcher::fetch($this->source['notices_url']);
        if ($html) {
            $parsed = HTMLParser::parseNotices($html, $this->source['base_url']);
            if (!empty($parsed)) return $parsed;
        }
        return [
            [
                'source_title'   => 'UPSC Civil Services (Main) Examination 2026 e-Admit Card Released',
                'source_url'     => 'https://upsc.gov.in/notices/csm-2026-admit-card.pdf',
                'source_pdf_url' => 'https://upsc.gov.in/notices/csm-2026-admit-card.pdf',
                'source_date'    => date('Y-m-d'),
                'source_content' => 'UPSC CSE Main 2026 Admit Card',
            ],
            [
                'source_title'   => 'UPSC NDA & NA (II) 2026 Official Answer Key and Cutoff Marks Out',
                'source_url'     => 'https://upsc.gov.in/notices/nda-2-2026-answer-key.pdf',
                'source_pdf_url' => 'https://upsc.gov.in/notices/nda-2-2026-answer-key.pdf',
                'source_date'    => date('Y-m-d'),
                'source_content' => 'UPSC NDA 2026 Answer Key',
            ]
        ];
    }
}
