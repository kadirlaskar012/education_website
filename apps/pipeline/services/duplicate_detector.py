import hashlib
import re
from typing import Tuple, Optional
from apps.sources.models import SourceItem
from apps.articles.models import Article

class DuplicateDetector:
    """
    Multi-layer duplicate and update detection system using:
    1. Exact URL & Content Hash matching
    2. Normalized Title Hashing
    3. Update detection (same source URL, modified content)
    """

    @staticmethod
    def normalize_title(title: str) -> str:
        """
        Normalizes notice title by stripping noise, special characters, and extra spaces.
        """
        cleaned = title.lower()
        cleaned = re.sub(r'[\(\)\[\]\{\}\-_,.:;!?"\'/\\|]', ' ', cleaned)
        cleaned = re.sub(r'\s+', ' ', cleaned).strip()
        return cleaned

    @classmethod
    def compute_content_hash(cls, url: str, title: str, text_snippet: str = "") -> str:
        normalized = f"{url.strip()}|{cls.normalize_title(title)}|{text_snippet[:200].strip()}"
        return hashlib.sha256(normalized.encode('utf-8')).hexdigest()

    @classmethod
    def check_duplicate_or_update(
        cls, 
        source_id: int, 
        url: str, 
        title: str, 
        content_hash: str
    ) -> Tuple[str, Optional[Article], Optional[SourceItem]]:
        """
        Evaluates incoming item against database records.
        Returns:
            status: ('NEW', 'DUPLICATE', 'UPDATE')
            existing_article: Article instance if UPDATE, else None
            existing_source_item: SourceItem instance if DUPLICATE, else None
        """
        # 1. Exact SourceItem content hash match
        existing_item = SourceItem.objects.filter(source_id=source_id, content_hash=content_hash).first()
        if existing_item:
            return 'DUPLICATE', None, existing_item

        # 2. Check if the URL already exists in Published Articles
        existing_article = Article.objects.filter(official_url=url).first()
        if existing_article:
            # URL exists: Check if title or content significantly changed -> Update!
            norm_new = cls.normalize_title(title)
            norm_existing = cls.normalize_title(existing_article.title)
            
            if norm_new != norm_existing:
                # Content/title updated on the official server for same notice
                return 'UPDATE', existing_article, None
            else:
                # Same URL, same title -> Duplicate
                return 'DUPLICATE', existing_article, None

        # 3. Check normalized title hash among recent articles in same category / source
        norm_title = cls.normalize_title(title)
        recent_articles = Article.objects.filter(source_id=source_id).order_by('-created_at')[:30]
        for art in recent_articles:
            if cls.normalize_title(art.title) == norm_title:
                return 'DUPLICATE', art, None

        return 'NEW', None, None
