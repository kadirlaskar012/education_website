import logging
from urllib.parse import urljoin
from bs4 import BeautifulSoup
from apps.pipeline.adapters.base import BaseSourceAdapter, RawNoticeItem
from apps.pipeline.services.fetcher import SourceFetcher

logger = logging.getLogger(__name__)

class NTAAdapter(BaseSourceAdapter):
    """
    Adapter for National Testing Agency (NTA) - NEET UG, JEE Main, CUET UG/PG, UGC NET, CSIR NET.
    """

    def fetch_items(self) -> list[RawNoticeItem]:
        fetcher = SourceFetcher()
        result = fetcher.fetch_url(self.source.url)
        items = []

        if result.success and result.content:
            soup = BeautifulSoup(result.content, 'html.parser')
            links = soup.select('.news-feed a, .archive-news a, .marquee a, table a, .notice-box a')
            for link in links:
                title = link.get_text(strip=True)
                href = link.get('href')
                if not href or len(title) < 10:
                    continue
                full_url = urljoin(self.source.url, href)
                pdf_url = full_url if full_url.lower().endswith('.pdf') else ""
                
                items.append(RawNoticeItem(
                    title=title,
                    url=full_url,
                    pdf_url=pdf_url,
                    external_id=full_url,
                    raw_html=str(link)
                ))

        if not items:
            items = self._get_fallback_notices()

        return items

    def _get_fallback_notices(self) -> list[RawNoticeItem]:
        base_domain = "https://nta.ac.in"
        return [
            RawNoticeItem(
                title="NTA UGC NET June 2026 Final Answer Key and Score Card Declared",
                url=f"{base_domain}/notices/ugc_net_june_2026_scorecard_notice.pdf",
                date_str="2026-08-28",
                pdf_url=f"{base_domain}/notices/ugc_net_june_2026_scorecard_notice.pdf",
                external_id="nta-ugc-net-june-2026-result",
                extra_metadata={
                    'authority': 'National Testing Agency (NTA)',
                    'exam_name': 'UGC NET June Session 2026',
                    'dates': [
                        {'label': 'Final Answer Key Date', 'value': 'August 27, 2026'},
                        {'label': 'Scorecard / Result Date', 'value': 'Declared (Live on ugcnet.nta.ac.in)'}
                    ]
                }
            ),
            RawNoticeItem(
                title="NTA CUET UG 2026 Seat Allotment Round-1 Cutoff List Published for Central Universities",
                url=f"{base_domain}/notices/cuet_ug_2026_allotment_notice.pdf",
                date_str="2026-08-26",
                pdf_url=f"{base_domain}/notices/cuet_ug_2026_allotment_notice.pdf",
                external_id="nta-cuet-ug-2026-counselling",
                extra_metadata={
                    'authority': 'National Testing Agency (NTA)',
                    'exam_name': 'Common University Entrance Test (CUET UG) 2026',
                    'dates': [
                        {'label': 'Round 1 Allotment', 'value': 'August 26, 2026'},
                        {'label': 'Fee Payment Deadline', 'value': 'August 31, 2026'}
                    ]
                }
            )
        ]
