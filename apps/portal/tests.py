from django.test import TestCase, Client
from django.urls import reverse
from django.utils import timezone
from apps.categories.models import Category
from apps.sources.models import Source
from apps.articles.models import Article, ArticleTemplate

class EducationPortalTests(TestCase):
    def setUp(self):
        self.client = Client()

        # Category
        self.category = Category.objects.create(
            name="Results",
            slug="results",
            description="All official government exam results",
            display_order=1
        )

        # Source
        self.source = Source.objects.create(
            name="Staff Selection Commission (SSC)",
            url="https://ssc.gov.in/",
            official_domain="ssc.gov.in",
            parser="ssc",
            source_type="html"
        )

        # Template
        self.template = ArticleTemplate.objects.create(
            name="Result Template",
            template_type="result"
        )

        # Article
        self.article = Article.objects.create(
            title="SSC CGL 2026 Tier-1 Result Declared with Cutoff Marks",
            slug="ssc-cgl-2026-tier1-result-declared",
            excerpt="Staff Selection Commission has announced the SSC CGL 2026 Tier-1 Result.",
            content_html="<p>Official result declared for SSC CGL 2026 Tier-1 examination.</p>",
            structured_data={
                "authority": "Staff Selection Commission (SSC)",
                "exam_name": "SSC CGL 2026",
                "dates": [{"label": "Result Date", "value": "Declared"}],
                "important_links": [{"title": "Official Portal", "url": "https://ssc.gov.in", "is_primary": True}],
                "faq": [{"question": "How to check result?", "answer": "Check official portal ssc.gov.in."}]
            },
            category=self.category,
            template=self.template,
            source=self.source,
            official_url="https://ssc.gov.in/notices/cgl2026.pdf",
            status="published",
            is_breaking=True,
            is_featured=True,
            published_at=timezone.now()
        )

    def test_homepage_renders(self):
        response = self.client.get(reverse('home'))
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, "EduGov News")
        self.assertContains(response, self.article.title)

    def test_category_page_renders(self):
        response = self.client.get(reverse('category_detail', kwargs={'slug': 'results'}))
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, "Results")
        self.assertContains(response, self.article.title)

    def test_article_detail_renders(self):
        response = self.client.get(reverse('article_detail', kwargs={'slug': self.article.slug}))
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, self.article.title)
        self.assertContains(response, "ssc.gov.in")
        self.assertContains(response, "How to check result?")
        self.assertContains(response, "application/ld+json")

    def test_search_view(self):
        response = self.client.get(reverse('search') + '?q=SSC')
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, self.article.title)

    def test_sitemap_xml(self):
        response = self.client.get('/sitemap.xml')
        self.assertEqual(response.status_code, 200)
        self.assertIn('application/xml', response['Content-Type'])

    def test_rss_feed(self):
        response = self.client.get('/rss.xml')
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, self.article.title)

    def test_robots_txt(self):
        response = self.client.get('/robots.txt')
        self.assertEqual(response.status_code, 200)
        self.assertContains(response, "Sitemap:")

    def test_legal_pages(self):
        for page in ['about', 'contact', 'privacy_policy', 'terms_conditions', 'disclaimer', 'copyright_policy']:
            response = self.client.get(reverse(page))
            self.assertEqual(response.status_code, 200)
