from django.db import models
from django.utils.text import slugify
from django.urls import reverse
from django.utils import timezone
from apps.categories.models import Category
from apps.sources.models import Source, SourceItem

class ArticleTemplate(models.Model):
    TEMPLATE_TYPES = [
        ('result', 'Result Template'),
        ('admit_card', 'Admit Card Template'),
        ('recruitment', 'Recruitment / Vacancy Template'),
        ('exam', 'Exam / Notification Template'),
        ('answer_key', 'Answer Key Template'),
        ('general_news', 'General Education News Template'),
    ]

    name = models.CharField(max_length=100)
    template_type = models.CharField(max_length=30, choices=TEMPLATE_TYPES, unique=True)
    description = models.TextField(blank=True)
    structure_json = models.JSONField(default=dict, help_text="Template schema defining required sections, fields, and default FAQ structure")
    is_active = models.BooleanField(default=True)

    def __str__(self):
        return f"{self.name} ({self.get_template_type_display()})"


class Article(models.Model):
    STATUS_CHOICES = [
        ('draft', 'Draft'),
        ('processing', 'Processing'),
        ('review', 'Pending Review'),
        ('published', 'Published'),
        ('updated', 'Updated'),
        ('rejected', 'Rejected'),
        ('duplicate', 'Duplicate'),
        ('error', 'Error'),
    ]

    title = models.CharField(max_length=350, help_text="Headline for the article")
    slug = models.SlugField(max_length=380, unique=True, blank=True, db_index=True)
    excerpt = models.TextField(max_length=600, blank=True, help_text="Short 2-3 sentence summary for news lists and SEO")
    content_html = models.TextField(help_text="Full semantic HTML content generated from official source")
    
    # Structured key-value data extracted directly from official notice
    # Holds: exam_name, authority, status_tag, vacancy, eligibility, age_limit, fees, important_dates, steps, important_links, faq, etc.
    structured_data = models.JSONField(default=dict, blank=True, help_text="Structured facts (dates, links, steps, eligibility, FAQs)")

    category = models.ForeignKey(Category, on_delete=models.PROTECT, related_name='articles')
    template = models.ForeignKey(ArticleTemplate, on_delete=models.SET_NULL, null=True, blank=True, related_name='articles')
    source = models.ForeignKey(Source, on_delete=models.SET_NULL, null=True, blank=True, related_name='articles')
    source_item = models.ForeignKey(SourceItem, on_delete=models.SET_NULL, null=True, blank=True, related_name='articles')
    
    official_url = models.URLField(max_length=1000, help_text="Direct official notice webpage")
    official_pdf_url = models.URLField(max_length=1000, blank=True, help_text="Official PDF download link if available")
    
    status = models.CharField(max_length=20, choices=STATUS_CHOICES, default='draft', db_index=True)
    is_featured = models.BooleanField(default=False, help_text="Feature in top editorial spot on homepage")
    is_breaking = models.BooleanField(default=False, help_text="Display in breaking updates ticker")
    
    views_count = models.PositiveIntegerField(default=0)
    reading_time_mins = models.PositiveIntegerField(default=2)
    
    published_at = models.DateTimeField(null=True, blank=True, db_index=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        verbose_name = "Article"
        verbose_name_plural = "Articles"
        ordering = ['-published_at', '-created_at']
        indexes = [
            models.Index(fields=['status', '-published_at']),
            models.Index(fields=['category', 'status', '-published_at']),
            models.Index(fields=['slug']),
        ]

    def save(self, *args, **kwargs):
        if not self.slug:
            base_slug = slugify(self.title)[:200]
            if not base_slug:
                base_slug = f"edu-notice-{timezone.now().strftime('%Y%m%d%H%M%S')}"
            unique_slug = base_slug
            num = 1
            while Article.objects.filter(slug=unique_slug).exclude(pk=self.pk).exists():
                unique_slug = f"{base_slug}-{num}"
                num += 1
            self.slug = unique_slug
            
        if self.status in ('published', 'updated') and not self.published_at:
            self.published_at = timezone.now()
            
        # Calculate reading time (~200 words per minute)
        word_count = len(self.content_html.split())
        self.reading_time_mins = max(1, round(word_count / 200))
        
        super().save(*args, **kwargs)

    def get_absolute_url(self):
        # Route through clean path based on category or standard /news/
        cat_slug = self.category.slug if self.category else 'news'
        if cat_slug in ('results', 'admit-card', 'recruitment', 'exam', 'answer-key'):
            return reverse('article_detail_by_cat', kwargs={'cat_slug': cat_slug, 'slug': self.slug})
        return reverse('article_detail', kwargs={'slug': self.slug})

    def __str__(self):
        return self.title


class ArticleVersion(models.Model):
    article = models.ForeignKey(Article, on_delete=models.CASCADE, related_name='versions')
    version_number = models.PositiveIntegerField(default=1)
    title = models.CharField(max_length=350)
    content_html = models.TextField()
    structured_data = models.JSONField(default=dict)
    change_summary = models.CharField(max_length=500, blank=True, help_text="Explanation of what changed in official notice")
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        verbose_name = "Article Version"
        verbose_name_plural = "Article Versions"
        ordering = ['-version_number']

    def __str__(self):
        return f"v{self.version_number} - {self.article.title[:50]}"


class ArticleLink(models.Model):
    LINK_TYPES = [
        ('related_result', 'Related Result'),
        ('related_admit_card', 'Related Admit Card'),
        ('related_exam', 'Related Exam Notice'),
        ('related_recruitment', 'Related Recruitment'),
        ('general_related', 'Related Article'),
    ]

    source_article = models.ForeignKey(Article, on_delete=models.CASCADE, related_name='outgoing_links')
    target_article = models.ForeignKey(Article, on_delete=models.CASCADE, related_name='incoming_links')
    link_type = models.CharField(max_length=30, choices=LINK_TYPES, default='general_related')
    anchor_text = models.CharField(max_length=200, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        verbose_name = "Article Internal Link"
        verbose_name_plural = "Article Internal Links"
        unique_together = ('source_article', 'target_article')

    def __str__(self):
        return f"{self.source_article.title[:30]} -> {self.target_article.title[:30]}"


class SEOMetadata(models.Model):
    article = models.OneToOneField(Article, on_delete=models.CASCADE, related_name='seo_meta')
    meta_title = models.CharField(max_length=200, blank=True)
    meta_description = models.CharField(max_length=300, blank=True)
    canonical_url = models.URLField(max_length=500, blank=True)
    og_image = models.URLField(max_length=500, blank=True)
    schema_type = models.CharField(max_length=50, default="NewsArticle")
    custom_json_ld = models.JSONField(default=dict, blank=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        verbose_name = "SEO Metadata"
        verbose_name_plural = "SEO Metadata"

    def __str__(self):
        return f"SEO for: {self.article.title[:50]}"
