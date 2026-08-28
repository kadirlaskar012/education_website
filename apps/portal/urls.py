from django.urls import path
from django.contrib.sitemaps.views import sitemap
from apps.portal import views
from apps.portal.sitemaps import ArticleSitemap, CategorySitemap, StaticViewSitemap
from apps.portal.feeds import LatestArticlesFeed

sitemaps_dict = {
    'articles': ArticleSitemap,
    'categories': CategorySitemap,
    'static': StaticViewSitemap,
}

urlpatterns = [
    # Core Portal Pages
    path('', views.home_view, name='home'),
    path('search/', views.search_view, name='search'),
    
    # Specific Category Short URLs
    path('results/', views.category_detail_view, {'slug': 'results'}, name='results_hub'),
    path('admit-card/', views.category_detail_view, {'slug': 'admit-card'}, name='admit_card_hub'),
    path('recruitment/', views.category_detail_view, {'slug': 'recruitment'}, name='recruitment_hub'),
    path('exam/', views.category_detail_view, {'slug': 'exam'}, name='exam_hub'),
    path('answer-key/', views.category_detail_view, {'slug': 'answer-key'}, name='answer_key_hub'),
    
    # General Category Detail
    path('category/<slug:slug>/', views.category_detail_view, name='category_detail'),

    # Legal & Information Pages
    path('about/', views.legal_page_view, {'page_name': 'about'}, name='about'),
    path('contact/', views.legal_page_view, {'page_name': 'contact'}, name='contact'),
    path('privacy-policy/', views.legal_page_view, {'page_name': 'privacy_policy'}, name='privacy_policy'),
    path('terms-and-conditions/', views.legal_page_view, {'page_name': 'terms_conditions'}, name='terms_conditions'),
    path('disclaimer/', views.legal_page_view, {'page_name': 'disclaimer'}, name='disclaimer'),
    path('copyright-policy/', views.legal_page_view, {'page_name': 'copyright_policy'}, name='copyright_policy'),

    # Feeds & SEO
    path('robots.txt', views.robots_txt_view, name='robots_txt'),
    path('sitemap.xml', sitemap, {'sitemaps': sitemaps_dict}, name='django.contrib.sitemaps.views.sitemap'),
    path('rss.xml', LatestArticlesFeed(), name='rss_feed'),
    path('feed/', LatestArticlesFeed(), name='feed'),

    # Admin quick actions
    path('admin-actions/fetch-source/<int:source_id>/', views.admin_trigger_source_fetch_view, name='admin_trigger_source_fetch'),

    # Article Detail Routes (keep specific /news/ and dynamic category /<cat_slug>/<slug>/)
    path('news/<slug:slug>/', views.article_detail_view, name='article_detail'),
    path('<slug:cat_slug>/<slug:slug>/', views.article_detail_view, name='article_detail_by_cat'),
]
