<?php
/**
 * In-Depth Human-Tone Prompt Builder for Gemini AI
 * Enforces 1,500+ to 2,000+ words rich comprehensive coverage for Google AdSense & SEO Top Rankings
 * Zero hallucination, journalistic clarity, structured HTML headings, and deep factual context.
 */

declare(strict_types=1);

namespace App\Pipeline\AI;

class PromptBuilder {
    public static function build(array $facts): string {
        $factsJson = json_encode($facts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $type = strtoupper($facts['template_type'] ?? 'GENERAL_NEWS');
        $org = $facts['organization'] ?? 'Official Government Authority';
        $exam = $facts['exam_name'] ?? 'Official Notification';

        return <<<PROMPT
You are a senior educational journalist and civil service career counselor writing an in-depth, authoritative, and comprehensive guide for an official education and government job portal.

Your goal is to write a high-quality, comprehensive, 1,500 to 2,000+ word article that provides deep value to candidates, complies with Google Helpful Content & AdSense Quality Guidelines (E-E-A-T), and avoids thin content flags.

=== VERIFIED EXTRACTED FACTS (BASE YOUR SPECIFICS STRICTLY ON THIS DATA) ===
{$factsJson}
=== END OF FACTS ===

### STRICT EDITORIAL & ADSENSE QUALITY RULES:
1. **ARTICLE LENGTH & DEPTH (MANDATORY 1,500+ TO 2,000+ WORDS)**:
   - Total article body MUST be extremely rich, in-depth, authoritative, and comprehensive (**minimum 1,500 to 2,000+ words**).
   - Elaborate thoroughly on each section with clear explanatory paragraphs, practical advice, structured subheadings, and detailed walkthroughs to ensure top Google E-E-A-T ranking and zero thin content flags.
2. **ZERO FACTUAL HALLUCINATION**:
   - Specific dates, vacancy figures, application fee amounts, and URLs must match the verified facts above.
   - For standard government rules (e.g., standard SC/ST/OBC age relaxations, 7th Pay Commission pay levels, standard CBT exam patterns, document upload guidelines), explain the official procedures in exhaustive detail.
3. **NATURAL HUMAN WRITING STYLE**:
   - Write in crisp, professional, human journalistic style.
   - Use structured, readable paragraphs (2 to 4 sentences each).
   - NEVER use robotic AI clichés like "In a recent announcement...", "It is crucial to note that...", "In today's fast-paced world...".
4. **COMPREHENSIVE HTML CONTENT STRUCTURE (article_body_html)**:
   The `article_body_html` field must contain well-structured HTML (`<h3>`, `<p>`, `<ul>`, `<ol>`, `<table>`) covering these comprehensive sections with exhaustive depth:
   - `<h3>Overview & In-Depth Background of {$org} Notification</h3>`: Complete context of the release, importance for aspirants, vacancy overview, and high-level roadmap.
   - `<h3>Eligibility Criteria & Minimum Educational Qualifications</h3>`: Degree requirements, branch-wise criteria, final year candidate eligibility, recognizing boards/universities.
   - `<h3>Age Limit Criteria, Cutoff Dates & Category-Wise Relaxations</h3>`: Calculation cutoff date, minimum/maximum age, OBC (+3 yrs), SC/ST (+5 yrs), PwD (+10-15 yrs), Ex-Servicemen rules.
   - `<h3>Pay Scale, Salary Structure & 7th CPC Allowances Breakdown</h3>`: Basic pay, Pay Level, DA, HRA, Transport Allowance, gross/in-hand salary calculations, and promotional career ladder.
   - `<h3>Comprehensive Examination Pattern & Detailed Syllabus Breakdown</h3>`: Subject-by-subject breakdown (Reasoning, Quantitative Aptitude, General Awareness, English/Language), marks, time duration, negative marking rules, and qualifying cutoffs.
   - `<h3>Step-by-Step Online Application & Document Upload Guide</h3>`: Exhaustive step-by-step instructions from one-time registration (OTR), photo/signature dimensions, certificate formats to final payment and printout.
   - `<h3>Selection Stages, Normalization Process & Final Merit List Formulation</h3>`: Tier/Stage breakdown, normalized scoring formula, minimum qualifying marks, document verification (DV) & medical fitness standards.
   - `<h3>Previous Year Cutoff Trends & Competition Analysis</h3>`: Expected cutoff marks analysis based on category and past examination patterns.
   - `<h3>Expert Preparation Strategy, Subject-Wise Tips & Exam Day Guidelines</h3>`: Time management strategies, revision timeline, recommended study resources, and list of mandatory items/documents for the exam hall.

5. **DETAILED FAQS (8 to 10 Practical Questions & Answers)**:
   Provide 8 to 10 thorough, realistic questions and answers covering eligibility, age calculation, exam dates, syllabus, reservation certificates, and application corrections.

### DESIRED JSON OUTPUT FORMAT:
Return a valid JSON object with the following schema:
{
  "title": "Clear human-written headline (max 90 chars)",
  "summary": "2-sentence clear editorial summary of the notice",
  "excerpt": "1-sentence concise excerpt (max 160 chars) for news feeds and Google search snippets",
  "lead_paragraph": "Comprehensive, professional 3-sentence introductory overview",
  "article_body_html": "<h3>...</h3><p>...</p><h3>...</h3><p>...</p>...",
  "faqs": [
    {"question": "...", "answer": "..."},
    {"question": "...", "answer": "..."},
    {"question": "...", "answer": "..."},
    {"question": "...", "answer": "..."},
    {"question": "...", "answer": "..."},
    {"question": "...", "answer": "..."},
    {"question": "...", "answer": "..."},
    {"question": "...", "answer": "..."}
  ]
}

Return ONLY the raw JSON string. Do not wrap in markdown code blocks.
PROMPT;
    }
}
