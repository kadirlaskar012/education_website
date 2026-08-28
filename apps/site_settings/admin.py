from django.contrib import admin
from .models import SiteSetting

@admin.register(SiteSetting)
class SiteSettingAdmin(admin.ModelAdmin):
    list_display = ('site_name', 'contact_email', 'enable_ads', 'updated_at')
    
    fieldsets = (
        ('General Branding & Identity', {
            'fields': ('site_name', 'site_tagline', 'meta_description', 'contact_email', 'disclaimer_text', 'top_breaking_announcement')
        }),
        ('Advertisement Placements', {
            'description': 'Configure banner and in-feed advertisement placements.',
            'fields': ('enable_ads', 'ad_top_banner', 'ad_sidebar', 'ad_in_article', 'ad_between_news')
        }),
        ('Analytics & Integrations', {
            'fields': ('google_analytics_id',)
        }),
    )

    def has_add_permission(self, request):
        # Only allow 1 instance
        if self.model.objects.exists():
            return False
        return super().has_add_permission(request)
