from dataclasses import dataclass, field
from typing import List, Dict, Any, Optional
from abc import ABC, abstractmethod

@dataclass
class RawNoticeItem:
    title: str
    url: str
    date_str: str = ""
    pdf_url: str = ""
    raw_html: str = ""
    external_id: str = ""
    extra_metadata: Dict[str, Any] = field(default_factory=dict)


class BaseSourceAdapter(ABC):
    """
    Base class for all modular source adapters.
    Each adapter handles:
    1. Fetching notices list
    2. Extracting notice links & attached PDFs
    3. Parsing notice details
    """

    def __init__(self, source):
        self.source = source

    @abstractmethod
    def fetch_items(self) -> List[RawNoticeItem]:
        """
        Fetches the latest notices from the official website / feed / API.
        Returns a list of RawNoticeItem objects.
        """
        pass

    def extract_pdf_data(self, pdf_bytes: bytes, original_title: str) -> Dict[str, Any]:
        from apps.pipeline.services.pdf_parser import PDFProcessor
        return PDFProcessor.parse_notice_pdf(pdf_bytes, original_title)
