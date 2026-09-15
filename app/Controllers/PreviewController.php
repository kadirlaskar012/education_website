<?php
/**
 * Interactive Live Theme & UI Showcase Controller
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;

class PreviewController extends Controller {
    public function index(): void {
        $articleModel = new Article();
        $sampleArticle = $articleModel->getLatestArticles(1)[0] ?? null;

        if (!$sampleArticle) {
            $sampleArticle = [
                'id'                   => 999,
                'title'                => 'RRB NTPC 2026 Recruitment Notification Out — 11,558 Vacancies, Apply Online Form, Exam Dates',
                'slug'                 => 'rrb-ntpc-2026-recruitment-notification-apply-online',
                'category_name'        => 'Recruitment',
                'category_slug'        => 'recruitment',
                'official_source_name' => 'Railway Recruitment Boards (RRB)',
                'official_source_url'  => 'https://www.rrbapply.gov.in',
                'official_pdf_url'     => 'https://www.rrbapply.gov.in/notices/CEN_01_2026.pdf',
                'excerpt'              => 'Railway Recruitment Board has officially issued the CEN 01/2026 notification for 11,558 Graduate and Undergraduate posts. Complete details on eligibility, syllabus, salary and application link.',
                'content_html'         => '<p>Railway Recruitment Boards (RRB) have officially released the Centralized Employment Notice (CEN 01/2026) for non-technical popular categories (NTPC). Eligible candidates can submit their online applications across 21 railway zones in India.</p><p>Candidates holding Graduate and 12th pass qualifications can apply before the stipulated deadline. Selection will be based on 2-tier Computer Based Tests (CBT-1 & CBT-2) followed by document verification.</p>',
                'published_at'         => date('Y-m-d H:i:s'),
                'views_count'          => 14250,
                'version_number'       => 1,
            ];
        }

        $this->render('portal/preview_themes', [
            'page_title'       => 'Live UI & Color Showcase — EduGov News',
            'meta_description' => 'Interactive live preview of 3 premium color palettes and modern UI architectures.',
            'article'          => $sampleArticle,
        ]);
    }
}
