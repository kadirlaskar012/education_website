from django.db import models

class SiteSetting(models.Model):
    site_name = models.CharField(max_length=150, default="EduGov News")
    site_tagline = models.CharField(max_length=250, default="Instant & Verified Official Education Updates, Results, Admit Cards & Jobs")
    meta_description = models.TextField(default="Official education news portal providing real-time government notifications, exam schedules, admit cards, recruitment notices, and result direct links.")
    contact_email = models.EmailField(default="contact@edugovnews.org")
    disclaimer_text = models.TextField(
        default="Disclaimer: This website is an independent education information portal. We curate and aggregate public notifications from official government portals. We are not associated with any government ministry, board, or commission. Always refer to official government websites for primary validation."
    )
    top_breaking_announcement = models.CharField(max_length=300, blank=True, help_text="Global pinned notice displayed in the top bar")
    
    # Ad Units Toggle & HTML snippets
    enable_ads = models.BooleanField(default=False, help_text="Toggle display of reserved ad slots")
    ad_top_banner = models.TextField(blank=True, help_text="HTML / Script code for 728x90 top header banner")
    ad_sidebar = models.TextField(blank=True, help_text="HTML / Script code for 300x250 or 300x600 sidebar ad")
    ad_in_article = models.TextField(blank=True, help_text="HTML / Script code for in-content ad slot")
    ad_between_news = models.TextField(blank=True, help_text="HTML / Script code for news list separator ad")
    
    google_analytics_id = models.CharField(max_length=50, blank=True, help_text="e.g. G-XXXXXXXXXX")
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        verbose_name = "Site Setting"
        verbose_name_plural = "Site Settings"

    def __str__(self):
        return f"{self.site_name} Configuration"

    @classmethod
    def get_settings(cls):
        obj, created = cls.objects.get_or_create(id=1)
        return obj
