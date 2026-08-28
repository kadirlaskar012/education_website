import json
from django.shortcuts import render, get_object_or_404, redirect
from django.core.paginator import Paginator, EmptyPage, PageNotAnInteger
from django.db.models import F, Q
from django.http import HttpResponse, Http404
from django.contrib import messages
from django.contrib.admin.views.decorators import staff_member_required

from apps.articles.models import Article, ArticleLink
from apps.categories.models import Category
from apps.sources.models import Source
from apps.site_settings.models import SiteSetting
from apps.pipeline.services.pipeline_runner import PipelineRunner

def home_view(request):
    """
    Main Homepage View: Clean news portal structure with breaking news,
    featured updates, and categorized compact lists (Results, Admit Card, Recruitment, Exams).
    """
    published_qs = Article.objects.filter(
        status__in=['published', 'updated']
    ).select_related('category', 'source').order_by('-published_at')

    # Top Featured
    featured_article = published_qs.filter(is_featured=True).first()
    if not featured_article:
        featured_article = published_qs.first()

    # Latest General News (excluding featured to avoid duplication)
    latest_news = published_qs.exclude(
        id=featured_article.id if featured_article else 0
    )[:8]

    # Category Sections
    results_list = published_qs.filter(
        category__slug__in=['results', 'answer-key']
    )[:5]

    admit_cards_list = published_qs.filter(
        category__slug__in=['admit-card', 'exam-date']
    )[:5]

    recruitment_list = published_qs.filter(
        category__slug__in=['recruitment', 'government-jobs', 'application-form']
    )[:5]

    exams_list = published_qs.filter(
        category__slug__in=['exam', 'entrance-exams', 'board-exams', 'important-updates']
    )[:5]

    # All active categories for category pill cloud
    categories = Category.objects.filter(is_active=True).order_by('display_order')

    context = {
        'featured_article': featured_article,
        'latest_news': latest_news,
        'results_list': results_list,
        'admit_cards_list': admit_cards_list,
        'recruitment_list': recruitment_list,
        'exams_list': exams_list,
        'categories': categories,
    }
    return render(request, 'portal/home.html', context)


def category_detail_view(request, slug):
    """
    Category Archive View with pagination and clean news listing.
    """
    category = get_object_or_404(Category, slug=slug, is_active=True)
    articles_qs = Article.objects.filter(
        category=category,
        status__in=['published', 'updated']
    ).select_related('category', 'source').order_by('-published_at')

    paginator = Paginator(articles_qs, 15)
    page = request.GET.get('page', 1)
    try:
        articles = paginator.page(page)
    except PageNotAnInteger:
        articles = paginator.page(1)
    except EmptyPage:
        articles = paginator.page(paginator.num_pages)

    context = {
        'category': category,
        'articles': articles,
        'total_count': paginator.count,
    }
    return render(request, 'portal/category.html', context)


def article_detail_view(request, slug, cat_slug=None):
    """
    Structured Article Page with strict official source attribution,
    Important Dates, Step-by-Step guides, direct links, Schema JSON-LD, and FAQs.
    """
    if request.user.is_staff:
        article = get_object_or_404(Article.objects.select_related('category', 'source', 'template'), slug=slug)
    else:
        article = get_object_or_404(
            Article.objects.select_related('category', 'source', 'template'), 
            slug=slug, 
            status__in=['published', 'updated']
        )

    # Increment view counter asynchronously
    Article.objects.filter(id=article.id).update(views_count=F('views_count') + 1)

    # Fetch structured facts
    structured_data = article.structured_data or {}
    dates_list = structured_data.get('dates', [])
    steps_list = structured_data.get('steps', [])
    links_list = structured_data.get('important_links', [])
    faq_list = structured_data.get('faq', [])

    # Related articles
    outgoing_related = [link.target_article for link in article.outgoing_links.select_related('target_article')[:4]]
    if len(outgoing_related) < 4:
        cat_related = Article.objects.filter(
            category=article.category, 
            status__in=['published', 'updated']
        ).exclude(id=article.id).order_by('-published_at')[:4 - len(outgoing_related)]
        outgoing_related.extend(cat_related)

    # Build Schema.org JSON-LD (NewsArticle + FAQPage + BreadcrumbList)
    site_settings = SiteSetting.get_settings()
    base_url = request.build_absolute_uri('/')[:-1]
    canonical_url = request.build_absolute_uri(article.get_absolute_url())

    json_ld_schemas = [
        {
            "@context": "https://schema.org",
            "@type": "NewsArticle",
            "headline": article.title,
            "description": article.excerpt,
            "url": canonical_url,
            "datePublished": article.published_at.isoformat() if article.published_at else article.created_at.isoformat(),
            "dateModified": article.updated_at.isoformat(),
            "publisher": {
                "@type": "Organization",
                "name": site_settings.site_name,
                "url": base_url
            },
            "mainEntityOfPage": {
                "@type": "WebPage",
                "@id": canonical_url
            }
        },
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
                {
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Home",
                    "item": base_url
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "name": article.category.name,
                    "item": request.build_absolute_uri(article.category.get_absolute_url())
                },
                {
                    "@type": "ListItem",
                    "position": 3,
                    "name": article.title,
                    "item": canonical_url
                }
            ]
        }
    ]

    if faq_list:
        faq_entities = [
            {
                "@type": "Question",
                "name": f.get('question'),
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": f.get('answer')
                }
            }
            for f in faq_list if f.get('question') and f.get('answer')
        ]
        if faq_entities:
            json_ld_schemas.append({
                "@context": "https://schema.org",
                "@type": "FAQPage",
                "mainEntity": faq_entities
            })

    context = {
        'article': article,
        'structured_data': structured_data,
        'dates_list': dates_list,
        'steps_list': steps_list,
        'links_list': links_list,
        'faq_list': faq_list,
        'related_articles': outgoing_related,
        'canonical_url': canonical_url,
        'json_ld_str': json.dumps(json_ld_schemas),
    }
    return render(request, 'portal/article_detail.html', context)


def search_view(request):
    """
    Search Portal View with multi-keyword query, category filters, and highlight snippets.
    """
    query = request.GET.get('q', '').strip()
    cat_slug = request.GET.get('cat', '').strip()
    
    results = Article.objects.filter(status__in=['published', 'updated']).select_related('category')

    if query:
        results = results.filter(
            Q(title__icontains=query) |
            Q(excerpt__icontains=query) |
            Q(content_html__icontains=query)
        )

    if cat_slug:
        results = results.filter(category__slug=cat_slug)

    results = results.order_by('-published_at')

    paginator = Paginator(results, 15)
    page = request.GET.get('page', 1)
    try:
        articles = paginator.page(page)
    except PageNotAnInteger:
        articles = paginator.page(1)
    except EmptyPage:
        articles = paginator.page(paginator.num_pages)

    categories = Category.objects.filter(is_active=True).order_by('name')

    context = {
        'query': query,
        'selected_cat': cat_slug,
        'articles': articles,
        'total_count': paginator.count,
        'categories': categories,
    }
    return render(request, 'portal/search.html', context)


def legal_page_view(request, page_name):
    """
    Renders legal and compliance pages: About Us, Contact, Privacy, Terms, Disclaimer, Copyright.
    """
    templates_map = {
        'about': ('About Us', 'portal/legal/about.html'),
        'contact': ('Contact Us', 'portal/legal/contact.html'),
        'privacy_policy': ('Privacy Policy', 'portal/legal/privacy.html'),
        'terms_conditions': ('Terms & Conditions', 'portal/legal/terms.html'),
        'disclaimer': ('Disclaimer', 'portal/legal/disclaimer.html'),
        'copyright_policy': ('Copyright & Source Attribution Policy', 'portal/legal/copyright.html'),
    }

    if page_name not in templates_map:
        raise Http404("Page not found")

    title, template_path = templates_map[page_name]

    if request.method == 'POST' and page_name == 'contact':
        name = request.POST.get('name', '')
        email = request.POST.get('email', '')
        subject = request.POST.get('subject', '')
        message = request.POST.get('message', '')
        messages.success(request, f"Thank you {name}! Your message regarding '{subject}' has been received.")

    context = {
        'page_title': title,
        'page_name': page_name,
    }
    return render(request, template_path, context)


def robots_txt_view(request):
    """
    Dynamic robots.txt view pointing to Sitemap & RSS feed.
    """
    host = request.get_host()
    scheme = 'https' if request.is_secure() else 'http'
    content = f"""User-agent: *
Allow: /

Sitemap: {scheme}://{host}/sitemap.xml
"""
    return HttpResponse(content.strip(), content_type="text/plain")


@staff_member_required
def admin_trigger_source_fetch_view(request, source_id):
    """
    Admin action to manually trigger immediate ingestion pipeline for a given source.
    """
    source = get_object_or_404(Source, pk=source_id)
    runner = PipelineRunner()
    res = runner.run_single_source(source)
    messages.success(
        request, 
        f"Fetch completed for {source.name}. Found: {res.get('items_found')}, New: {res.get('items_new')}, Duplicate: {res.get('items_duplicate')}"
    )
    return redirect(request.META.get('HTTP_REFERER', '/admin/sources/source/'))
