<?php
/**
 * Scraper Adapter Registry
 */

namespace App\Pipeline\Adapters;

class Registry {
    public static function getAdapter(array $source): BaseAdapter {
        $adapterClass = $source['adapter_class'] ?? 'SSCAdapter';
        $fullClass = 'App\\Pipeline\\Adapters\\' . $adapterClass;

        if (class_exists($fullClass)) {
            return new $fullClass($source);
        }

        $slug = $source['slug'] ?? '';
        if (str_contains($slug, 'upsc')) {
            return new UPSCAdapter($source);
        }
        if (str_contains($slug, 'rrb') || str_contains($slug, 'railway')) {
            return new RailwayAdapter($source);
        }
        if (str_contains($slug, 'nta')) {
            return new NTAAdapter($source);
        }
        if (str_contains($slug, 'ibps') || str_contains($slug, 'sbi') || str_contains($slug, 'rbi') || str_contains($slug, 'bank')) {
            return new BankingAdapter($source);
        }
        if (str_contains($slug, 'jee') || str_contains($slug, 'aiims') || str_contains($slug, 'nbe') || str_contains($slug, 'cbse') || str_contains($slug, 'cisce')) {
            return new EntranceAdapter($source);
        }
        if (str_contains($slug, 'army') || str_contains($slug, 'iaf') || str_contains($slug, 'navy') || str_contains($slug, 'defense')) {
            return new DefenseAdapter($source);
        }
        if (str_contains($slug, 'wbpsc') || str_contains($slug, 'wbprb') || str_contains($slug, 'uppsc') || str_contains($slug, 'upsssc') || str_contains($slug, 'bpsc') || str_contains($slug, 'rpsc') || str_contains($slug, 'mppsc') || str_contains($slug, 'mpsc')) {
            return new StatePSCAdapter($source);
        }
        if (str_contains($slug, 'nsp') || str_contains($slug, 'ugc') || str_contains($slug, 'aicte') || str_contains($slug, 'scholarship')) {
            return new ScholarshipAdapter($source);
        }

        return new SSCAdapter($source);
    }
}
