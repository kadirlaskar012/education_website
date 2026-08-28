from django.contrib import admin
from django.utils.html import format_html
from django.utils import timezone
from .models import Article, ArticleTemplate, ArticleVersion, ArticleLink, SEOMetadata

class ArticleVersionInline(admin.TabularInline):
    model = ArticleVersion
    extra = 0
    readonly_fields = ('version_number', 'change_summary', 'created_at')
    can_delete = False


class ArticleLinkInline(admin.TabularInline):
    model = ArticleLink
    fk_name = 'source_article'
    extra = 1


class SEOMetadataInline(admin.StackedInline):
    model = SEOMetadata
    extra = 0


@admin.register(ArticleTemplate)
class ArticleTemplateAdmin(admin.ModelAdmin):
    list_display = ('name', 'template_type', 'is_active')
    list_filter = ('template_type', 'is_active')
    search_fields = ('name', 'description')


@admin.register(Article)
class ArticleAdmin(admin.ModelAdmin):
    list_display = ('title_truncated', 'category_badge', 'status_badge', 'source_info', 'views_count', 'is_breaking', 'is_featured', 'published_at', 'live_link')
    list_editable = ('is_breaking', 'is_featured')
    list_filter = ('status', 'category', 'is_breaking', 'is_featured', 'template', 'source', 'published_at')
    search_fields = ('title', 'excerpt', 'slug', 'official_url')
    prepopulated_fields = {'slug': ('title',)}
    date_hierarchy = 'published_at'
    inlines = [SEOMetadataInline, ArticleVersionInline, ArticleLinkInline]
    actions = ['publish_selected', 'unpublish_selected', 'mark_breaking', 'mark_featured']
    
    fieldsets = (
        ('Basic Information', {
            'fields': ('title', 'slug', 'category', 'status', 'excerpt', 'content_html')
        }),
        ('Official Source Information', {
            'fields': ('source', 'source_item', 'official_url', 'official_pdf_url')
        }),
        ('Structured Data (Extracted Facts)', {
            'classes': ('collapse',),
            'fields': ('template', 'structured_data')
        }),
        ('Editorial Flags & Metrics', {
            'fields': ('is_breaking', 'is_featured', 'views_count', 'reading_time_mins', 'published_at')
        }),
    )

    def title_truncated(self, obj):
        return obj.title[:85] + ('...' if len(obj.title) > 85 else '')
    title_truncated.short_description = "Headline"

    def category_badge(self, obj):
        return format_html(
            '<span style="background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; font-weight: 500; font-size: 11px;">{}</span>',
            obj.category.name if obj.category else 'None'
        )
    category_badge.short_description = "Category"

    def status_badge(self, obj):
        colors = {
            'draft': '#64748b',
            'processing': '#0284c7',
            'review': '#d97706',
            'published': '#16a34a',
            'updated': '#0d9488',
            'rejected': '#dc2626',
            'duplicate': '#9333ea',
            'error': '#b91c1c',
        }
        color = colors.get(obj.status, '#64748b')
        return format_html(
            '<span style="background-color: {}; color: white; padding: 2px 6px; border-radius: 3px; font-weight: 600; font-size: 11px; text-transform: uppercase;">{}</span>',
            color, obj.get_status_display()
        )
    status_badge.short_description = "Status"

    def source_info(self, obj):
        if obj.source:
            return obj.source.name
        return "Manual"
    source_info.short_description = "Source"

    def live_link(self, obj):
        if obj.pk:
            url = obj.get_absolute_url()
            return format_html('<a href="{}" target="_blank" style="color: #2563eb; font-weight: bold;">View ↗</a>', url)
        return "-"
    live_link.short_description = "Preview"

    @admin.action(description="Publish Selected Articles")
    def publish_selected(self, request, queryset):
        count = queryset.update(status='published', published_at=timezone.now())
        self.message_user(request, f"{count} article(s) published successfully.")

    @admin.action(description="Unpublish / Move to Review")
    def unpublish_selected(self, request, queryset):
        count = queryset.update(status='review')
        self.message_user(request, f"{count} article(s) moved to Pending Review.")

    @admin.action(description="Mark as Breaking Update")
    def mark_breaking(self, request, queryset):
        count = queryset.update(is_breaking=True)
        self.message_user(request, f"{count} article(s) marked as Breaking.")

    @admin.action(description="Mark as Featured Headline")
    def mark_featured(self, request, queryset):
        count = queryset.update(is_featured=True)
        self.message_user(request, f"{count} article(s) marked as Featured.")


@admin.register(ArticleVersion)
class ArticleVersionAdmin(admin.ModelAdmin):
    list_display = ('article', 'version_number', 'change_summary', 'created_at')
    search_fields = ('article__title', 'change_summary')
    readonly_fields = ('article', 'version_number', 'title', 'content_html', 'structured_data', 'change_summary', 'created_at')


@admin.register(SEOMetadata)
class SEOMetadataAdmin(admin.ModelAdmin):
    list_display = ('article', 'meta_title', 'schema_type', 'updated_at')
    search_fields = ('article__title', 'meta_title', 'meta_description')
