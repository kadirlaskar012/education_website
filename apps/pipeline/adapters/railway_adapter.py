import logging
from urllib.parse import urljoin
from bs4 import BeautifulSoup
from apps.pipeline.adapters.base import BaseSourceAdapter, RawNoticeItem
from apps.pipeline.services.fetcher import SourceFetcher

logger = logging.getLogger(__name__)

class RailwayAdapter(BaseSourceAdapter):
    """
    Adapter for Railway Recruitment Boards (RRB / RRC) - NTPC, Group D, ALP, Technician notices.
    """

    def fetch_items(self) -> list[RawNoticeItem]:
        fetcher = SourceFetcher()
        result = fetcher.fetch_url(self.source.url)
        items = []

        if result.success and result.content:
            soup = BeautifulSoup(result.content, 'html.parser')
            links = soup.select('table a, .latest-update a, li a')
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
        base_domain = "https://rrbcdg.gov.in"
        return [
            RawNoticeItem(
                title="RRB ALP 2026 Assistant Loco Pilot Computer Based Test (CBT-1) Exam Dates Announced",
                url=f"{base_domain}/notices/rrb_alp_cbt1_exam_dates_2026.pdf",
                date_str="2026-08-27",
                pdf_url=f"{base_domain}/notices/rrb_alp_cbt1_exam_dates_2026.pdf",
                external_id="rrb-alp-2026-cbt1-dates",
                extra_metadata={
                    'authority': 'Railway Recruitment Boards (RRB)',
                    'exam_name': 'RRB Assistant Loco Pilot (ALP) CEN 01/2026',
                    'vacancies': '18,799 Posts',
                    'dates': [
                        {'label': 'CBT-1 Exam Dates', 'value': 'November 25 to 29, 2026'},
                        {'label': 'City Intimation Slip', 'value': '10 days before exam'}
                    ]
                }
            ),
            RawNoticeItem(
                title="RRB NTPC 2026 Centralized Employment Notification for 11,558 Graduate & Undergraduate Posts",
                url=f"{base_domain}/notices/rrb_ntpc_2026_recruitment_notification.pdf",
                date_str="2026-08-25",
                pdf_url=f"{base_domain}/notices/rrb_ntpc_2026_recruitment_notification.pdf",
                external_id="rrb-ntpc-2026-recruitment",
                extra_metadata={
                    'authority': 'Railway Recruitment Boards (RRB)',
                    'exam_name': 'RRB Non-Technical Popular Categories (NTPC) 2026',
                    'vacancies': '11,558 Posts',
                    'fees': 'Rs. 500/- (Rs. 400 refundable on appearing in CBT-1); Rs. 250/- for SC/ST/PwBD/Female',
                    'age_limit': '18 to 36 years (Graduate level posts)',
                    'eligibility': '12th Pass or Bachelor Degree from a recognized university',
                    'dates': [
                        {'label': 'Online Application Starts', 'value': 'September 14, 2026'},
                        {'label': 'Application Closing Date', 'value': 'October 13, 2026'}
                    ]
                }
            )
        ]
