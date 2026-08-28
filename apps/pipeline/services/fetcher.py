import logging
import time
import requests
import urllib3
from dataclasses import dataclass
from typing import Optional, Dict, Any
from django.conf import settings

# Suppress InsecureRequestWarning for government portals with expired SSL certs
urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

logger = logging.getLogger(__name__)

@dataclass
class FetchResult:
    success: bool
    status_code: int
    content: Optional[str] = None
    binary_content: Optional[bytes] = None
    headers: Optional[Dict[str, str]] = None
    error: Optional[str] = None
    duration_ms: int = 0
    content_type: str = "text/html"


class SourceFetcher:
    """
    Robust HTTP Fetcher with fast timeout, retry logic, realistic browser headers,
    and fallback mechanisms for educational notices & PDF downloads.
    """
    
    def __init__(self, user_agent: Optional[str] = None, timeout: int = 6):
        self.user_agent = user_agent or getattr(
            settings, 
            'DEFAULT_USER_AGENT', 
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36 EducationBot/1.0'
        )
        self.timeout = timeout
        self.session = requests.Session()
        self.session.headers.update({
            'User-Agent': self.user_agent,
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,application/pdf,*/*;q=0.8',
            'Accept-Language': 'en-US,en;q=0.9,hi;q=0.8',
            'Cache-Control': 'no-cache',
        })

    def fetch_url(self, url: str, is_binary: bool = False, max_retries: int = 1) -> FetchResult:
        start_time = time.time()
        last_error = None

        for attempt in range(1, max_retries + 1):
            try:
                response = self.session.get(
                    url, 
                    timeout=self.timeout, 
                    allow_redirects=True, 
                    verify=False
                )
                duration_ms = int((time.time() - start_time) * 1000)

                content_type = response.headers.get('Content-Type', '').lower()

                if response.status_code == 200:
                    if is_binary or 'application/pdf' in content_type:
                        return FetchResult(
                            success=True,
                            status_code=response.status_code,
                            binary_content=response.content,
                            headers=dict(response.headers),
                            duration_ms=duration_ms,
                            content_type=content_type
                        )
                    else:
                        return FetchResult(
                            success=True,
                            status_code=response.status_code,
                            content=response.text,
                            headers=dict(response.headers),
                            duration_ms=duration_ms,
                            content_type=content_type
                        )
                else:
                    last_error = f"HTTP {response.status_code}: {response.reason}"
                    if response.status_code in [404, 403, 401]:
                        break

            except requests.exceptions.RequestException as e:
                last_error = f"Request error on attempt {attempt}: {str(e)}"
                logger.warning(f"Fetch failed for {url}: {e}")

        duration_ms = int((time.time() - start_time) * 1000)
        return FetchResult(
            success=False,
            status_code=0,
            error=last_error or "Unknown fetch error",
            duration_ms=duration_ms
        )
