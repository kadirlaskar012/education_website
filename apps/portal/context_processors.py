from django.utils import timezone
from apps.site_settings.models import SiteSetting
from apps.categories.models import Category
from apps.articles.models import Article
from apps.sources.models import Source, SourceItem

def global_portal_context(request):
    try:
        settings_obj = SiteSetting.get_settings()
    except Exception:
        settings_obj = None

    try:
        all_categories = list(Category.objects.filter(is_active=True).order_by('display_order'))
        
        # Primary top navbar categories
        primary_slugs = ['results', 'admit-card', 'recruitment', 'exam', 'answer-key', 'latest-news']
        primary_categories = [c for c in all_categories if c.slug in primary_slugs]
        # More dropdown categories (any active category not in primary)
        more_categories = [c for c in all_categories if c.slug not in primary_slugs]

        breaking_articles = Article.objects.filter(
            status__in=['published', 'updated'], 
            is_breaking=True
        ).select_related('category').order_by('-published_at')[:6]
        
        if not breaking_articles.exists():
            breaking_articles = Article.objects.filter(
                status__in=['published', 'updated']
            ).select_related('category').order_by('-published_at')[:3]

        trending_articles = Article.objects.filter(
            status__in=['published', 'updated']
        ).select_related('category').order_by('-views_count', '-published_at')[:5]

        # Admin dashboard stats
        article_stats = {
            'total': Article.objects.count(),
            'published': Article.objects.filter(status__in=['published', 'updated']).count(),
            'review': Article.objects.filter(status='review').count(),
            'draft': Article.objects.filter(status='draft').count(),
            'duplicate': SourceItem.objects.filter(status='duplicate').count(),
            'error': SourceItem.objects.filter(status='error').count(),
        }
        active_sources_count = Source.objects.filter(is_active=True).count()

    except Exception:
        all_categories = []
        primary_categories = []
        more_categories = []
        breaking_articles = []
        trending_articles = []
        article_stats = {}
        active_sources_count = 0

    return {
        'site_settings': settings_obj,
        'nav_categories': all_categories,
        'primary_categories': primary_categories,
        'more_categories': more_categories,
        'breaking_articles': breaking_articles,
        'trending_articles': trending_articles,
        'article_stats': article_stats,
        'active_sources_count': active_sources_count,
        'current_time': timezone.now(),
        'current_year': timezone.now().year,
    }
