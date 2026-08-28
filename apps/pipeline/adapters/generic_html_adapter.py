import logging
from urllib.parse import urljoin
from bs4 import BeautifulSoup
from apps.pipeline.adapters.base import BaseSourceAdapter, RawNoticeItem
from apps.pipeline.services.fetcher import SourceFetcher

logger = logging.getLogger(__name__)

class GenericHTMLAdapter(BaseSourceAdapter):
    """
    Configurable HTML scraper adapter accepting custom CSS selectors from source.config.
    """

    def fetch_items(self) -> list[RawNoticeItem]:
        fetcher = SourceFetcher()
        result = fetcher.fetch_url(self.source.url)
        items = []

        if not result.success or not result.content:
            return items

        soup = BeautifulSoup(result.content, 'html.parser')
        cfg = self.source.config or {}
        
        item_sel = cfg.get('item_selector', 'table tr, .notice-item, .views-row, ul.notices li, .list-group-item')
        title_sel = cfg.get('title_selector', 'a, .title, h3, h4')
        link_sel = cfg.get('link_selector', 'a')
        
        elements = soup.select(item_sel)
        for el in elements:
            link_el = el.select_one(link_sel) if link_sel else (el if el.name == 'a' else el.find('a'))
            if not link_el or not link_el.get('href'):
                continue
                
            href = link_el.get('href')
            full_url = urljoin(self.source.url, href)
            
            title_el = el.select_one(title_sel) if title_sel else link_el
            title = title_el.get_text(strip=True) if title_el else link_el.get_text(strip=True)
            
            if not title or len(title) < 8:
                continue

            pdf_url = full_url if full_url.lower().endswith('.pdf') else ""

            items.append(RawNoticeItem(
                title=title,
                url=full_url,
                pdf_url=pdf_url,
                external_id=full_url,
                raw_html=str(el)
            ))

        return items
