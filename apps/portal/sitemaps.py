from django.contrib.sitemaps import Sitemap
from django.urls import reverse
from apps.articles.models import Article
from apps.categories.models import Category

class ArticleSitemap(Sitemap):
    changefreq = "daily"
    priority = 0.9

    def items(self):
        return Article.objects.filter(status__in=['published', 'updated']).order_by('-published_at')

    def lastmod(self, obj):
        return obj.updated_at

    def location(self, obj):
        return obj.get_absolute_url()


class CategorySitemap(Sitemap):
    changefreq = "daily"
    priority = 0.7

    def items(self):
        return Category.objects.filter(is_active=True)

    def location(self, obj):
        return obj.get_absolute_url()


class StaticViewSitemap(Sitemap):
    priority = 0.5
    changefreq = "weekly"

    def items(self):
        return ['home', 'search', 'about', 'contact', 'privacy_policy', 'terms_conditions', 'disclaimer', 'copyright_policy']

    def location(self, item):
        return reverse(item)
