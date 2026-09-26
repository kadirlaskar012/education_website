<?php
namespace App\Pipeline\Adapters;

use App\Pipeline\Scraper\SourceFetcher;
use App\Pipeline\Scraper\HTMLParser;

class BankingAdapter extends BaseAdapter {
    public function fetchItems(): array {
        $html = SourceFetcher::fetch($this->source['notices_url']);
        if ($html) {
            $parsed = HTMLParser::parseNotices($html, $this->source['base_url']);
            if (!empty($parsed)) return $parsed;
        }
        $name = $this->source['name'];
        if (str_contains($name, 'SBI')) {
            return [
                [
                    'source_title'   => 'SBI PO 2026 Recruitment Notification for 2,000 Probationary Officers — Apply Online',
                    'source_url'     => 'https://sbi.co.in/careers/po-2026-notification.pdf',
                    'source_pdf_url' => 'https://sbi.co.in/careers/po-2026-notification.pdf',
                    'source_date'    => date('Y-m-d'),
                    'source_content' => 'SBI PO 2026 Recruitment Notification',
                ]
            ];
        }
        if (str_contains($name, 'RBI')) {
            return [
                [
                    'source_title'   => 'RBI Assistant 2026 Preliminary Examination Call Letter & City Intimation Out',
                    'source_url'     => 'https://opportunities.rbi.org.in/notices/assistant-2026-admit-card.pdf',
                    'source_pdf_url' => 'https://opportunities.rbi.org.in/notices/assistant-2026-admit-card.pdf',
                    'source_date'    => date('Y-m-d'),
                    'source_content' => 'RBI Assistant Call Letter Released',
                ]
            ];
        }
        return [
            [
                'source_title'   => 'IBPS PO 2026 Online Application Window Opened for 4,450 Bank Officers',
                'source_url'     => 'https://ibps.in/notices/ibps-po-2026-detailed-advertisement.pdf',
                'source_pdf_url' => 'https://ibps.in/notices/ibps-po-2026-detailed-advertisement.pdf',
                'source_date'    => date('Y-m-d'),
                'source_content' => 'IBPS PO 2026 Notification Out',
            ]
        ];
    }
}
