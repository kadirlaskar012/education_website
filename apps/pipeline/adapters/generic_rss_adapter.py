import logging
import xml.etree.ElementTree as ET
from urllib.parse import urljoin
from apps.pipeline.adapters.base import BaseSourceAdapter, RawNoticeItem
from apps.pipeline.services.fetcher import SourceFetcher

logger = logging.getLogger(__name__)

class GenericRSSAdapter(BaseSourceAdapter):
    """
    Standard RSS 2.0 / Atom feed adapter for education portals and news feeds.
    """

    def fetch_items(self) -> list[RawNoticeItem]:
        fetcher = SourceFetcher()
        result = fetcher.fetch_url(self.source.url)
        items = []

        if result.success and result.content:
            try:
                root = ET.fromstring(result.content)
                # RSS 2.0 channel -> item
                for item in root.findall('.//item'):
                    title = item.findtext('title', '').strip()
                    link = item.findtext('link', '').strip()
                    pub_date = item.findtext('pubDate', '').strip()
                    description = item.findtext('description', '').strip()
                    guid = item.findtext('guid', link).strip()
                    
                    pdf_url = link if link.lower().endswith('.pdf') else ""
                    # Also check enclosure
                    enclosure = item.find('enclosure')
                    if enclosure is not None and 'pdf' in enclosure.get('type', ''):
                        pdf_url = enclosure.get('url', '')

                    if title and link:
                        items.append(RawNoticeItem(
                            title=title,
                            url=link,
                            date_str=pub_date,
                            pdf_url=pdf_url,
                            external_id=guid,
                            raw_html=description
                        ))
                
                # Atom feed entry
                if not items:
                    for entry in root.findall('.//{http://www.w3.org/2005/Atom}entry'):
                        title = entry.findtext('{http://www.w3.org/2005/Atom}title', '').strip()
                        link_el = entry.find('{http://www.w3.org/2005/Atom}link')
                        link = link_el.get('href', '') if link_el is not None else ''
                        updated = entry.findtext('{http://www.w3.org/2005/Atom}updated', '').strip()
                        id_str = entry.findtext('{http://www.w3.org/2005/Atom}id', link).strip()

                        if title and link:
                            items.append(RawNoticeItem(
                                title=title,
                                url=link,
                                date_str=updated,
                                pdf_url=link if link.endswith('.pdf') else '',
                                external_id=id_str
                            ))
            except Exception as e:
                logger.error(f"Error parsing RSS XML for {self.source.name}: {e}")

        return items
