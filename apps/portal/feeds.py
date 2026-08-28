from django.contrib.syndication.views import Feed
from django.utils.feedgenerator import Rss201rev2Feed
from apps.articles.models import Article
from apps.site_settings.models import SiteSetting

class LatestArticlesFeed(Feed):
    feed_type = Rss201rev2Feed

    def title(self):
        settings = SiteSetting.get_settings()
        return f"{settings.site_name} - Latest Education News & Notifications"

    def link(self):
        return "/"

    def description(self):
        settings = SiteSetting.get_settings()
        return settings.site_tagline

    def items(self):
        return Article.objects.filter(status__in=['published', 'updated']).order_by('-published_at')[:30]

    def item_title(self, item):
        return item.title

    def item_description(self, item):
        return item.excerpt or item.title

    def item_pubdate(self, item):
        return item.published_at

    def item_link(self, item):
        return item.get_absolute_url()
