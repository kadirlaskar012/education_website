from django.db import models
from apps.categories.models import Category

class Source(models.Model):
    SOURCE_TYPE_CHOICES = [
        ('html', 'HTML Webpage'),
        ('pdf', 'PDF Direct Notice'),
        ('rss', 'RSS / Atom Feed'),
        ('api', 'JSON / REST API'),
        ('sitemap', 'XML Sitemap'),
    ]

    PARSER_CHOICES = [
        ('ssc', 'SSC (Staff Selection Commission)'),
        ('upsc', 'UPSC (Union Public Service Commission)'),
        ('nta', 'NTA (National Testing Agency)'),
        ('railway', 'Railway Recruitment (RRB / RRC)'),
        ('generic_rss', 'Generic RSS/Atom Feed'),
        ('generic_html', 'Generic HTML Notice Board'),
    ]

    STATUS_CHOICES = [
        ('idle', 'Idle / Ready'),
        ('fetching', 'Fetching'),
        ('success', 'Success'),
        ('error', 'Error'),
        ('disabled', 'Disabled'),
    ]

    name = models.CharField(max_length=150, unique=True, help_text="Source identifier e.g. 'SSC Official Notices'")
    url = models.URLField(max_length=500, help_text="Target URL to fetch notices/updates from")
    official_domain = models.CharField(max_length=200, help_text="e.g. 'ssc.gov.in', 'upsc.gov.in', 'nta.ac.in'")
    source_type = models.CharField(max_length=20, choices=SOURCE_TYPE_CHOICES, default='html')
    category = models.ForeignKey(Category, on_delete=models.SET_NULL, null=True, blank=True, related_name='sources', help_text="Default category if auto-detection falls back")
    parser = models.CharField(max_length=50, choices=PARSER_CHOICES, default='generic_html')
    fetch_frequency_minutes = models.PositiveIntegerField(default=30, help_text="Frequency to poll in minutes (e.g. 15, 30, 60)")
    is_active = models.BooleanField(default=True, help_text="Enable or disable automated fetching")
    auto_publish = models.BooleanField(default=True, help_text="Auto-publish articles if validated, or send to Admin Review")
    priority = models.PositiveIntegerField(default=10, help_text="Fetch priority (lower number = higher priority)")
    config = models.JSONField(default=dict, blank=True, help_text="Optional custom configuration (CSS selectors, custom headers, auth, etc.)")
    
    # Health & status tracking
    last_checked_at = models.DateTimeField(null=True, blank=True)
    last_success_at = models.DateTimeField(null=True, blank=True)
    last_failure_at = models.DateTimeField(null=True, blank=True)
    error_count = models.PositiveIntegerField(default=0)
    last_error_message = models.TextField(blank=True)
    status = models.CharField(max_length=20, choices=STATUS_CHOICES, default='idle')
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        verbose_name = "Source"
        verbose_name_plural = "Sources"
        ordering = ['priority', 'name']

    def __str__(self):
        return f"{self.name} ({self.get_parser_display()})"


class SourceItem(models.Model):
    STATUS_CHOICES = [
        ('new', 'New / Unprocessed'),
        ('processed', 'Processed'),
        ('duplicate', 'Duplicate Skipped'),
        ('error', 'Processing Error'),
    ]

    source = models.ForeignKey(Source, on_delete=models.CASCADE, related_name='items')
    external_id = models.CharField(max_length=255, blank=True, db_index=True, help_text="Unique ID or hash from source")
    title = models.CharField(max_length=500)
    url = models.URLField(max_length=1000)
    content_hash = models.CharField(max_length=64, db_index=True, help_text="SHA256 of normalized title & URL")
    raw_data = models.JSONField(default=dict, help_text="Extracted raw metadata, dates, links, and text")
    status = models.CharField(max_length=20, choices=STATUS_CHOICES, default='new')
    fetched_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        verbose_name = "Source Item"
        verbose_name_plural = "Source Items"
        ordering = ['-fetched_at']
        indexes = [
            models.Index(fields=['source', 'content_hash']),
            models.Index(fields=['status']),
        ]

    def __str__(self):
        return f"{self.title[:80]} [{self.source.name}]"


class RawDocument(models.Model):
    DOCUMENT_TYPES = [
        ('pdf', 'PDF Document'),
        ('html', 'HTML Snapshot'),
        ('json', 'JSON Response'),
    ]

    source_item = models.ForeignKey(SourceItem, on_delete=models.CASCADE, related_name='documents')
    document_type = models.CharField(max_length=20, choices=DOCUMENT_TYPES, default='pdf')
    file_url = models.URLField(max_length=1000)
    local_file = models.FileField(upload_to='raw_docs/%Y/%m/', null=True, blank=True)
    extracted_text = models.TextField(blank=True)
    metadata = models.JSONField(default=dict, blank=True)
    file_hash = models.CharField(max_length=64, blank=True, db_index=True)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        verbose_name = "Raw Document"
        verbose_name_plural = "Raw Documents"

    def __str__(self):
        return f"{self.document_type.upper()}: {self.file_url[:60]}"


class FetchLog(models.Model):
    STATUS_CHOICES = [
        ('success', 'Success'),
        ('partial', 'Partial'),
        ('failed', 'Failed'),
    ]

    source = models.ForeignKey(Source, on_delete=models.CASCADE, related_name='fetch_logs')
    status = models.CharField(max_length=20, choices=STATUS_CHOICES)
    http_status = models.IntegerField(null=True, blank=True)
    items_found = models.PositiveIntegerField(default=0)
    items_new = models.PositiveIntegerField(default=0)
    items_duplicate = models.PositiveIntegerField(default=0)
    error_message = models.TextField(blank=True)
    duration_ms = models.PositiveIntegerField(default=0)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        verbose_name = "Fetch Log"
        verbose_name_plural = "Fetch Logs"
        ordering = ['-created_at']


class ProcessingLog(models.Model):
    STAGE_CHOICES = [
        ('fetch', 'Source Fetch'),
        ('parse', 'Content Parsing'),
        ('pdf', 'PDF Processing'),
        ('classify', 'Category & Template Classification'),
        ('dedup', 'Duplicate Detection'),
        ('generate', 'Article Generation'),
        ('validate', 'Data Validation'),
        ('publish', 'Publishing'),
    ]

    STATUS_CHOICES = [
        ('info', 'Info'),
        ('success', 'Success'),
        ('warning', 'Warning'),
        ('error', 'Error'),
    ]

    source_item = models.ForeignKey(SourceItem, on_delete=models.CASCADE, null=True, blank=True, related_name='processing_logs')
    article = models.ForeignKey('articles.Article', on_delete=models.SET_NULL, null=True, blank=True, related_name='processing_logs')
    stage = models.CharField(max_length=20, choices=STAGE_CHOICES)
    status = models.CharField(max_length=20, choices=STATUS_CHOICES, default='info')
    message = models.CharField(max_length=500)
    error_details = models.TextField(blank=True)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        verbose_name = "Processing Log"
        verbose_name_plural = "Processing Logs"
        ordering = ['-created_at']
