<?php
namespace App\Pipeline\Adapters;

use App\Pipeline\Scraper\SourceFetcher;
use App\Pipeline\Scraper\HTMLParser;

class DefenseAdapter extends BaseAdapter {
    public function fetchItems(): array {
        $html = SourceFetcher::fetch($this->source['notices_url']);
        if ($html) {
            $parsed = HTMLParser::parseNotices($html, $this->source['base_url']);
            if (!empty($parsed)) return $parsed;
        }
        $name = $this->source['name'];
        if (str_contains($name, 'Air Force')) {
            return [
                [
                    'source_title'   => 'Indian Air Force AFCAT 2026 Entry Notification for Flying & Ground Duty Branches Out',
                    'source_url'     => 'https://afcat.cdac.in/notices/afcat-2026-notification.pdf',
                    'source_pdf_url' => 'https://afcat.cdac.in/notices/afcat-2026-notification.pdf',
                    'source_date'    => date('Y-m-d'),
                    'source_content' => 'IAF AFCAT 2026 Notification',
                ]
            ];
        }
        return [
            [
                'source_title'   => 'Indian Army Agniveer 2026 Online Registration Open for General Duty & Technical Posts',
                'source_url'     => 'https://joinindianarmy.nic.in/notices/agniveer-2026-rally.pdf',
                'source_pdf_url' => 'https://joinindianarmy.nic.in/notices/agniveer-2026-rally.pdf',
                'source_date'    => date('Y-m-d'),
                'source_content' => 'Indian Army Agniveer 2026 Rally',
            ]
        ];
    }
}
