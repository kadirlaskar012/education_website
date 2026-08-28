from django.db import models
from django.utils.text import slugify
from django.urls import reverse

class Category(models.Model):
    name = models.CharField(max_length=120, unique=True, help_text="Name of the education category")
    slug = models.SlugField(max_length=150, unique=True, blank=True, help_text="URL slug")
    description = models.TextField(blank=True, help_text="Brief description for SEO and category page header")
    icon = models.CharField(max_length=60, default="newspaper", blank=True, help_text="Icon identifier (e.g. newspaper, award, id-card, book)")
    display_order = models.PositiveIntegerField(default=0, help_text="Ordering in navigation and lists (lower numbers come first)")
    is_active = models.BooleanField(default=True, help_text="Whether this category is displayed publicly")
    meta_title = models.CharField(max_length=200, blank=True, help_text="SEO Meta Title (optional override)")
    meta_description = models.CharField(max_length=300, blank=True, help_text="SEO Meta Description (optional override)")
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        verbose_name = "Category"
        verbose_name_plural = "Categories"
        ordering = ['display_order', 'name']
        indexes = [
            models.Index(fields=['slug']),
            models.Index(fields=['is_active', 'display_order']),
        ]

    def save(self, *args, **kwargs):
        if not self.slug:
            self.slug = slugify(self.name)
        super().save(*args, **kwargs)

    def get_absolute_url(self):
        return reverse('category_detail', kwargs={'slug': self.slug})

    def __str__(self):
        return self.name
