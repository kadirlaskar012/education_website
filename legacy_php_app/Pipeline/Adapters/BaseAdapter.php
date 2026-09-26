<?php
/**
 * Base Scraper Adapter
 */

namespace App\Pipeline\Adapters;

use App\Pipeline\Scraper\HTMLParser;

abstract class BaseAdapter {
    protected array $source;

    public function __construct(array $source) {
        $this->source = $source;
    }

    abstract public function fetchItems(): array;

    protected function cleanUrl(string $url): string {
        return HTMLParser::resolveUrl($url, $this->source['base_url']);
    }
}
