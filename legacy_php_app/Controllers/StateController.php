<?php
/**
 * State-Wise Filter Controller
 * Handles /state/{slug} queries
 */

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;

class StateController extends Controller {
    private static array $stateMap = [
        'central-govt'   => ['code' => 'ALL', 'name' => 'All India & Central Government'],
        'all-india'      => ['code' => 'ALL', 'name' => 'All India & Central Government'],
        'west-bengal'    => ['code' => 'WB', 'name' => 'West Bengal'],
        'uttar-pradesh'  => ['code' => 'UP', 'name' => 'Uttar Pradesh'],
        'bihar'          => ['code' => 'BIHAR', 'name' => 'Bihar'],
        'rajasthan'      => ['code' => 'RAJ', 'name' => 'Rajasthan'],
        'madhya-pradesh' => ['code' => 'MP', 'name' => 'Madhya Pradesh'],
        'maharashtra'    => ['code' => 'MAH', 'name' => 'Maharashtra'],
    ];

    public function show(string $slug): void {
        $slug = strtolower(trim($slug));
        $stateInfo = self::$stateMap[$slug] ?? null;

        if (!$stateInfo) {
            $this->redirect('/');
        }

        $articleModel = new Article();
        $articles = $articleModel->getByStateCode($stateInfo['code'], 30);

        $this->render('portal/state', [
            'page_title'       => "{$stateInfo['name']} Government Jobs & Education Notices — EduGov News",
            'meta_description' => "Latest {$stateInfo['name']} government recruitment, state PSC exam dates, admit cards, and results.",
            'state_name'       => $stateInfo['name'],
            'state_slug'       => $slug,
            'state_code'       => $stateInfo['code'],
            'articles'         => $articles,
            'all_states'       => self::$stateMap,
        ]);
    }
}
