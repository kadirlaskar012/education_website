import time
import logging
from typing import Dict, Any, List
from django.utils import timezone
from django.db import transaction

from apps.sources.models import Source, SourceItem, RawDocument, FetchLog, ProcessingLog
from apps.categories.models import Category
from apps.articles.models import Article, ArticleTemplate, ArticleVersion, ArticleLink, SEOMetadata
from apps.pipeline.adapters.registry import get_adapter_instance
from apps.pipeline.services.fetcher import SourceFetcher
from apps.pipeline.services.pdf_parser import PDFProcessor
from apps.pipeline.services.duplicate_detector import DuplicateDetector
from apps.pipeline.services.article_generator import ArticleGenerator
from apps.pipeline.services.validator import ArticleValidator

logger = logging.getLogger(__name__)

class PipelineRunner:
    """
    Central Orchestrator executing the complete Education News pipeline end-to-end:
    Fetcher -> Change Detection -> PDF Extraction -> Normalization -> Classification ->
    Duplicate Check -> Article Generation -> Validation -> Publishing -> Internal Linking.
    """

    def run_all_active_sources(self) -> List[Dict[str, Any]]:
        active_sources = Source.objects.filter(is_active=True).order_by('priority')
        results = []
        for source in active_sources:
            try:
                res = self.run_single_source(source)
                results.append(res)
            except Exception as e:
                logger.error(f"Fatal error running source {source.name}: {e}", exc_info=True)
                results.append({'source': source.name, 'success': False, 'error': str(e)})
        return results

    def run_single_source(self, source: Source) -> Dict[str, Any]:
        start_time = time.time()
        source.status = 'fetching'
        source.save(update_fields=['status'])

        items_found = 0
        items_new = 0
        items_duplicate = 0
        items_updated = 0
        items_error = 0
        error_msg = ""
        http_status = 200

        try:
            adapter = get_adapter_instance(source)
            raw_items = adapter.fetch_items()
            items_found = len(raw_items)

            for raw_item in raw_items:
                try:
                    outcome = self._process_raw_notice(source, raw_item)
                    if outcome == 'NEW':
                        items_new += 1
                    elif outcome == 'DUPLICATE':
                        items_duplicate += 1
                    elif outcome == 'UPDATE':
                        items_updated += 1
                    else:
                        items_error += 1
                except Exception as item_err:
                    logger.error(f"Error processing item {raw_item.title}: {item_err}", exc_info=True)
                    items_error += 1

            source.status = 'success'
            source.last_checked_at = timezone.now()
            source.last_success_at = timezone.now()
            source.error_count = 0
            source.last_error_message = ""
            source.save(update_fields=['status', 'last_checked_at', 'last_success_at', 'error_count', 'last_error_message'])

            fetch_status = 'success' if items_error == 0 else ('partial' if items_new > 0 else 'failed')

        except Exception as e:
            error_msg = str(e)
            source.status = 'error'
            source.last_checked_at = timezone.now()
            source.last_failure_at = timezone.now()
            source.error_count += 1
            source.last_error_message = error_msg
            source.save(update_fields=['status', 'last_checked_at', 'last_failure_at', 'error_count', 'last_error_message'])
            fetch_status = 'failed'
            http_status = 500

        duration_ms = int((time.time() - start_time) * 1000)

        # Log fetch execution
        FetchLog.objects.create(
            source=source,
            status=fetch_status,
            http_status=http_status,
            items_found=items_found,
            items_new=items_new,
            items_duplicate=items_duplicate,
            error_message=error_msg,
            duration_ms=duration_ms
        )

        return {
            'source': source.name,
            'status': fetch_status,
            'items_found': items_found,
            'items_new': items_new,
            'items_duplicate': items_duplicate,
            'items_updated': items_updated,
            'duration_ms': duration_ms,
        }

    def _process_raw_notice(self, source: Source, raw_item) -> str:
        """
        Processes a single notice through the pipeline.
        Returns: 'NEW', 'DUPLICATE', 'UPDATE', or 'ERROR'
        """
        content_hash = DuplicateDetector.compute_content_hash(
            url=raw_item.url,
            title=raw_item.title,
            text_snippet=raw_item.raw_html
        )

        dup_status, existing_article, existing_source_item = DuplicateDetector.check_duplicate_or_update(
            source_id=source.id,
            url=raw_item.url,
            title=raw_item.title,
            content_hash=content_hash
        )

        if dup_status == 'DUPLICATE':
            # Record duplicate in SourceItem if not already recorded
            SourceItem.objects.get_or_create(
                source=source,
                content_hash=content_hash,
                defaults={
                    'external_id': raw_item.external_id or raw_item.url,
                    'title': raw_item.title,
                    'url': raw_item.url,
                    'raw_data': {'html': raw_item.raw_html, 'meta': raw_item.extra_metadata},
                    'status': 'duplicate'
                }
            )
            return 'DUPLICATE'

        # 1. PDF / Notice Data Extraction
        parsed_pdf_data = {}
        pdf_bytes = None
        if raw_item.pdf_url:
            fetcher = SourceFetcher()
            pdf_fetch = fetcher.fetch_url(raw_item.pdf_url, is_binary=True)
            if pdf_fetch.success and pdf_fetch.binary_content:
                pdf_bytes = pdf_fetch.binary_content
                parsed_pdf_data = PDFProcessor.parse_notice_pdf(pdf_bytes, raw_item.title)

        # Merge any pre-parsed adapter metadata
        if raw_item.extra_metadata:
            for k, v in raw_item.extra_metadata.items():
                if v and (k not in parsed_pdf_data or not parsed_pdf_data[k] or parsed_pdf_data[k] == "Not specified in the official notification."):
                    parsed_pdf_data[k] = v

        if not parsed_pdf_data.get('dates') and raw_item.date_str:
            parsed_pdf_data['dates'] = [{'label': 'Notice Date', 'value': raw_item.date_str}]

        # 2. Generate Article Structure
        article_payload = ArticleGenerator.generate_article_payload(
            raw_title=raw_item.title,
            official_url=raw_item.url,
            source_name=source.name,
            official_domain=source.official_domain,
            parsed_pdf_data=parsed_pdf_data,
            official_pdf_url=raw_item.pdf_url
        )

        # 3. Validate
        is_valid, validation_errors = ArticleValidator.validate_article_payload(article_payload)
        if not is_valid:
            logger.warning(f"Validation failed for notice '{raw_item.title}': {validation_errors}")
            return 'ERROR'

        # 4. Handle UPDATE vs NEW
        with transaction.atomic():
            # Resolve Category
            cat_slug = article_payload['category_slug']
            category = Category.objects.filter(slug=cat_slug, is_active=True).first()
            if not category:
                category = source.category or Category.objects.filter(is_active=True).first()

            # Resolve Template
            template_type = article_payload['template_type']
            template, _ = ArticleTemplate.objects.get_or_create(
                template_type=template_type,
                defaults={'name': f"{template_type.replace('_', ' ').title()} Template", 'is_active': True}
            )

            if dup_status == 'UPDATE' and existing_article:
                # Save previous version
                current_ver_count = existing_article.versions.count()
                ArticleVersion.objects.create(
                    article=existing_article,
                    version_number=current_ver_count + 1,
                    title=existing_article.title,
                    content_html=existing_article.content_html,
                    structured_data=existing_article.structured_data,
                    change_summary=f"Official update received on {timezone.now().strftime('%Y-%m-%d %H:%M')}"
                )

                # Update article fields while preserving original slug and URL
                existing_article.title = article_payload['title']
                existing_article.excerpt = article_payload['excerpt']
                existing_article.content_html = article_payload['content_html']
                existing_article.structured_data = article_payload['structured_data']
                existing_article.status = 'updated'
                existing_article.save()

                ProcessingLog.objects.create(
                    article=existing_article,
                    stage='publish',
                    status='success',
                    message=f"Updated article '{existing_article.title}' to version {current_ver_count + 1}"
                )
                return 'UPDATE'

            # Create SourceItem
            source_item = SourceItem.objects.create(
                source=source,
                external_id=raw_item.external_id or raw_item.url,
                title=raw_item.title,
                url=raw_item.url,
                content_hash=content_hash,
                raw_data={'html': raw_item.raw_html, 'meta': raw_item.extra_metadata},
                status='processed'
            )

            # Store RawDocument if PDF
            if pdf_bytes:
                RawDocument.objects.create(
                    source_item=source_item,
                    document_type='pdf',
                    file_url=raw_item.pdf_url,
                    extracted_text=parsed_pdf_data.get('raw_text', ''),
                    metadata={'dates': parsed_pdf_data.get('dates')},
                    file_hash=content_hash
                )

            # Determine publication status
            publish_status = 'published' if source.auto_publish else 'review'
            pub_date = timezone.now() if publish_status == 'published' else None

            # Create Article
            article = Article.objects.create(
                title=article_payload['title'],
                excerpt=article_payload['excerpt'],
                content_html=article_payload['content_html'],
                structured_data=article_payload['structured_data'],
                category=category,
                template=template,
                source=source,
                source_item=source_item,
                official_url=article_payload['official_url'],
                official_pdf_url=article_payload['official_pdf_url'],
                status=publish_status,
                published_at=pub_date
            )

            # Create SEO Metadata
            SEOMetadata.objects.create(
                article=article,
                meta_title=f"{article.title} - Official Update",
                meta_description=article.excerpt[:280],
                canonical_url=article.official_url,
                schema_type="NewsArticle"
            )

            # Internal linking: Find related articles by authority / exam name
            self._link_related_articles(article)

            # Processing log
            ProcessingLog.objects.create(
                source_item=source_item,
                article=article,
                stage='publish',
                status='success',
                message=f"Successfully generated and published article '{article.title}' (Status: {publish_status})"
            )

        return 'NEW'

    def _link_related_articles(self, article: Article):
        """
        Creates semantic internal links between related articles (e.g. Result <-> Exam <-> Admit Card).
        """
        exam_name = article.structured_data.get('exam_name', '')
        authority = article.structured_data.get('authority', '')
        
        if not exam_name or len(exam_name) < 4:
            return

        # Query recent articles with matching authority and overlapping title words
        keywords = [w for w in exam_name.split() if len(w) > 3 and w.lower() not in ['examination', 'exam', 'posts', 'recruitment']]
        if not keywords:
            return

        query_filter = Article.objects.exclude(id=article.id).filter(status__in=['published', 'updated'])
        for kw in keywords[:2]:
            matches = query_filter.filter(title__icontains=kw)[:3]
            for match in matches:
                # Link both ways if not existing
                ArticleLink.objects.get_or_create(
                    source_article=article,
                    target_article=match,
                    defaults={'link_type': 'general_related', 'anchor_text': match.title}
                )
