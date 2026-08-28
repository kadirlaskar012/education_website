from django.contrib import admin
from django.utils.html import format_html
from django.urls import reverse
from django.shortcuts import redirect
from django.contrib import messages
from .models import Source, SourceItem, RawDocument, FetchLog, ProcessingLog

@admin.register(Source)
class SourceAdmin(admin.ModelAdmin):
    list_display = ('name', 'parser', 'source_type', 'priority', 'auto_publish', 'status_badge', 'last_checked_at', 'error_count', 'is_active', 'fetch_now_button')
    list_editable = ('priority', 'auto_publish', 'is_active')
    list_filter = ('is_active', 'status', 'parser', 'source_type', 'auto_publish')
    search_fields = ('name', 'url', 'official_domain')
    actions = ['trigger_fetch_selected', 'activate_sources', 'deactivate_sources']

    def status_badge(self, obj):
        colors = {
            'idle': '#4b5563',
            'fetching': '#2563eb',
            'success': '#16a34a',
            'error': '#dc2626',
            'disabled': '#9ca3af',
        }
        color = colors.get(obj.status, '#4b5563')
        return format_html(
            '<span style="background-color: {}; color: white; padding: 3px 8px; border-radius: 3px; font-weight: 600; font-size: 11px; text-transform: uppercase;">{}</span>',
            color, obj.get_status_display()
        )
    status_badge.short_description = "Status"

    def fetch_now_button(self, obj):
        return format_html(
            '<a class="button" href="{}" style="background-color: #1e3a8a; color: white; padding: 4px 8px; border-radius: 4px; text-decoration: none; font-size: 11px;">Fetch Now</a>',
            reverse('admin_trigger_source_fetch', args=[obj.pk])
        )
    fetch_now_button.short_description = "Action"

    @admin.action(description="Trigger Immediate Fetch for Selected Sources")
    def trigger_fetch_selected(self, request, queryset):
        from apps.pipeline.services.pipeline_runner import PipelineRunner
        runner = PipelineRunner()
        total_items = 0
        for source in queryset:
            res = runner.run_single_source(source)
            total_items += res.get('items_found', 0)
        self.message_user(request, f"Completed fetch for {queryset.count()} sources. Total items found: {total_items}", messages.SUCCESS)

    @admin.action(description="Enable Selected Sources")
    def activate_sources(self, request, queryset):
        queryset.update(is_active=True)

    @admin.action(description="Disable Selected Sources")
    def deactivate_sources(self, request, queryset):
        queryset.update(is_active=False)


@admin.register(SourceItem)
class SourceItemAdmin(admin.ModelAdmin):
    list_display = ('title_truncated', 'source', 'status', 'fetched_at')
    list_filter = ('status', 'source', 'fetched_at')
    search_fields = ('title', 'url', 'content_hash')
    readonly_fields = ('source', 'external_id', 'title', 'url', 'content_hash', 'raw_data', 'fetched_at')

    def title_truncated(self, obj):
        return obj.title[:100] + ('...' if len(obj.title) > 100 else '')
    title_truncated.short_description = "Title"


@admin.register(RawDocument)
class RawDocumentAdmin(admin.ModelAdmin):
    list_display = ('document_type', 'source_item', 'file_hash', 'created_at')
    list_filter = ('document_type', 'created_at')
    search_fields = ('file_url', 'extracted_text', 'file_hash')


@admin.register(FetchLog)
class FetchLogAdmin(admin.ModelAdmin):
    list_display = ('source', 'status', 'http_status', 'items_found', 'items_new', 'items_duplicate', 'duration_ms', 'created_at')
    list_filter = ('status', 'source', 'created_at')
    search_fields = ('source__name', 'error_message')
    readonly_fields = ('source', 'status', 'http_status', 'items_found', 'items_new', 'items_duplicate', 'error_message', 'duration_ms', 'created_at')


@admin.register(ProcessingLog)
class ProcessingLogAdmin(admin.ModelAdmin):
    list_display = ('stage', 'status', 'message', 'source_item', 'article', 'created_at')
    list_filter = ('stage', 'status', 'created_at')
    search_fields = ('message', 'error_details')
    readonly_fields = ('source_item', 'article', 'stage', 'status', 'message', 'error_details', 'created_at')
