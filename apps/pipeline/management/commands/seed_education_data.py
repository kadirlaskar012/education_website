from django.core.management.base import BaseCommand
from django.utils import timezone
from apps.categories.models import Category
from apps.sources.models import Source
from apps.articles.models import ArticleTemplate, Article
from apps.site_settings.models import SiteSetting
from apps.pipeline.services.pipeline_runner import PipelineRunner

class Command(BaseCommand):
    help = "Seeds all categories, templates, official government sources, and executes initial pipeline ingestion"

    def handle(self, *args, **options):
        self.stdout.write(self.style.NOTICE("Initializing Education News Portal Data Seed..."))

        # 1. Seed Categories (All 14 Required Categories)
        categories_data = [
            {'name': 'Latest News', 'slug': 'latest-news', 'icon': 'newspaper', 'display_order': 1, 'description': 'Breaking updates and general education headlines'},
            {'name': 'Results', 'slug': 'results', 'icon': 'trophy', 'display_order': 2, 'description': 'Government exam results, scorecards, merit lists and cut-offs'},
            {'name': 'Admit Card', 'slug': 'admit-card', 'icon': 'id-badge', 'display_order': 3, 'description': 'Hall tickets, e-Admit cards, and city intimation slips'},
            {'name': 'Exam', 'slug': 'exam', 'icon': 'graduation-cap', 'display_order': 4, 'description': 'National and state level examination notices and guidelines'},
            {'name': 'Recruitment', 'slug': 'recruitment', 'icon': 'briefcase', 'display_order': 5, 'description': 'Government job openings, vacancies, and recruitment notices'},
            {'name': 'Admission', 'slug': 'admission', 'icon': 'university', 'display_order': 6, 'description': 'College, university, and school admission updates'},
            {'name': 'Scholarship', 'slug': 'scholarship', 'icon': 'medal', 'display_order': 7, 'description': 'National, state, and international scholarship schemes'},
            {'name': 'Answer Key', 'slug': 'answer-key', 'icon': 'key', 'display_order': 8, 'description': 'Provisional and final answer keys with objection trackers'},
            {'name': 'Exam Date', 'slug': 'exam-date', 'icon': 'calendar', 'display_order': 9, 'description': 'Official examination schedules, datesheets, and time tables'},
            {'name': 'Application Form', 'slug': 'application-form', 'icon': 'edit', 'display_order': 10, 'description': 'Live online application forms, deadlines, and registration links'},
            {'name': 'Board Exams', 'slug': 'board-exams', 'icon': 'book', 'display_order': 11, 'description': 'CBSE, ICSE, and State Board 10th & 12th updates'},
            {'name': 'Entrance Exams', 'slug': 'entrance-exams', 'icon': 'award', 'display_order': 12, 'description': 'JEE, NEET, CUET, GATE, CAT entrance test information'},
            {'name': 'Government Jobs', 'slug': 'government-jobs', 'icon': 'building', 'display_order': 13, 'description': 'Central & State Sarkari Naukri notifications'},
            {'name': 'Important Updates', 'slug': 'important-updates', 'icon': 'bell', 'display_order': 14, 'description': 'Critical circulars, deadline extensions, and public notices'},
        ]

        cat_objs = {}
        for item in categories_data:
            cat, created = Category.objects.get_or_create(
                slug=item['slug'],
                defaults=item
            )
            cat_objs[item['slug']] = cat
            if created:
                self.stdout.write(self.style.SUCCESS(f"  + Category: {cat.name}"))

        # 2. Seed Templates
        templates_data = [
            ('result', 'Result Template', 'Structured format for declaration of exam results and scorecards'),
            ('admit_card', 'Admit Card Template', 'Format for hall tickets and examination entry cards'),
            ('recruitment', 'Recruitment Template', 'Detailed vacancies, eligibility, fees, and application steps'),
            ('exam', 'Exam Template', 'Examination patterns, schedules, and official notices'),
            ('answer_key', 'Answer Key Template', 'Key objection tracker and provisional answers'),
            ('general_news', 'General News Template', 'Standard clean education news format'),
        ]
        for code, name, desc in templates_data:
            tmpl, created = ArticleTemplate.objects.get_or_create(
                template_type=code,
                defaults={'name': name, 'description': desc, 'is_active': True}
            )
            if created:
                self.stdout.write(self.style.SUCCESS(f"  + Template: {name}"))

        # 3. Seed Site Settings
        SiteSetting.get_settings()
        self.stdout.write(self.style.SUCCESS("  + Site Settings verified"))

        # 4. Seed Official Government Sources
        sources_data = [
            {
                'name': 'Staff Selection Commission (SSC)',
                'url': 'https://ssc.gov.in/',
                'official_domain': 'ssc.gov.in',
                'source_type': 'html',
                'category': cat_objs.get('recruitment'),
                'parser': 'ssc',
                'fetch_frequency_minutes': 15,
                'is_active': True,
                'auto_publish': True,
                'priority': 1,
            },
            {
                'name': 'Union Public Service Commission (UPSC)',
                'url': 'https://upsc.gov.in/',
                'official_domain': 'upsc.gov.in',
                'source_type': 'html',
                'category': cat_objs.get('exam'),
                'parser': 'upsc',
                'fetch_frequency_minutes': 15,
                'is_active': True,
                'auto_publish': True,
                'priority': 2,
            },
            {
                'name': 'National Testing Agency (NTA)',
                'url': 'https://nta.ac.in/',
                'official_domain': 'nta.ac.in',
                'source_type': 'html',
                'category': cat_objs.get('entrance-exams'),
                'parser': 'nta',
                'fetch_frequency_minutes': 20,
                'is_active': True,
                'auto_publish': True,
                'priority': 3,
            },
            {
                'name': 'Railway Recruitment Boards (RRB)',
                'url': 'https://rrbcdg.gov.in/',
                'official_domain': 'rrbcdg.gov.in',
                'source_type': 'html',
                'category': cat_objs.get('government-jobs'),
                'parser': 'railway',
                'fetch_frequency_minutes': 30,
                'is_active': True,
                'auto_publish': True,
                'priority': 4,
            },
        ]

        for s_data in sources_data:
            src, created = Source.objects.get_or_create(
                name=s_data['name'],
                defaults=s_data
            )
            if created:
                self.stdout.write(self.style.SUCCESS(f"  + Source: {src.name}"))

        # 5. Run initial automatic ingestion through the pipeline
        self.stdout.write(self.style.NOTICE("Running automated ingestion pipeline across all configured sources..."))
        runner = PipelineRunner()
        results = runner.run_all_active_sources()
        for res in results:
            self.stdout.write(self.style.SUCCESS(
                f"  -> {res.get('source')}: {res.get('items_found')} found, {res.get('items_new')} published, {res.get('items_duplicate')} duplicate"
            ))

        # Mark top article as breaking / featured for rich homepage preview
        first_art = Article.objects.filter(status='published').first()
        if first_art:
            first_art.is_breaking = True
            first_art.is_featured = True
            first_art.save()

        self.stdout.write(self.style.SUCCESS("\n[SUCCESS] Education News Website successfully seeded with categories, templates, sources, and live articles!"))
