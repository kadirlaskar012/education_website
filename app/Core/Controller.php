<?php
/**
 * Base Controller
 */

namespace App\Core;

abstract class Controller {
    protected function render(string $view, array $data = [], string $layout = 'main'): void {
        View::render($view, $data, $layout);
    }

    protected function json(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect(string $url): void {
        header("Location: {$url}");
        exit;
    }
}
