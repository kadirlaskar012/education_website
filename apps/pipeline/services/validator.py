import re
from typing import Tuple, List, Dict, Any

class ArticleValidator:
    """
    Validates article payloads prior to database insertion and publishing.
    Ensures no corrupted or incomplete data enters the public news stream.
    """

    @classmethod
    def validate_article_payload(cls, payload: Dict[str, Any]) -> Tuple[bool, List[str]]:
        errors = []
        
        title = payload.get('title', '').strip()
        if not title:
            errors.append("Article title is missing or empty.")
        elif len(title) < 8:
            errors.append("Article title is too short (< 8 characters).")
        elif len(title) > 350:
            errors.append("Article title exceeds maximum length (350 characters).")

        content_html = payload.get('content_html', '').strip()
        if not content_html:
            errors.append("Article content HTML is missing.")
        elif len(content_html) < 150:
            errors.append("Article content HTML is too short (< 150 characters).")

        official_url = payload.get('official_url', '').strip()
        if not official_url:
            errors.append("Official source URL is required.")
        elif not (official_url.startswith('http://') or official_url.startswith('https://')):
            errors.append("Official source URL must be a valid HTTP/HTTPS URL.")

        # Check structured data requirements
        structured = payload.get('structured_data', {})
        if not isinstance(structured, dict):
            errors.append("Structured data must be a valid dictionary.")
        else:
            if not structured.get('important_links'):
                errors.append("At least one official link must be present.")

        is_valid = len(errors) == 0
        return is_valid, errors
