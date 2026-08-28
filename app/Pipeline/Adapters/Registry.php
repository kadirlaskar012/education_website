<?php
/**
 * Adapter Registry
 */

namespace App\Pipeline\Adapters;

require_once __DIR__ . '/BaseAdapter.php';

class Registry {
    public static function getAdapter(array $source): BaseAdapter {
        $adapterName = $source['adapter_class'] ?? 'HTMLAdapter';
        $class = "App\\Pipeline\\Adapters\\" . $adapterName;
        if (class_exists($class)) {
            return new $class($source);
        }
        return new SSCAdapter($source);
    }
}
