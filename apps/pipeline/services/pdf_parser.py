import io
import re
import logging
from typing import Dict, Any, List
from pypdf import PdfReader

logger = logging.getLogger(__name__)

class PDFProcessor:
    """
    Extracts text and key structured attributes (dates, vacancies, fees, eligibility)
    from official government educational notifications and PDF documents.
    """

    @staticmethod
    def extract_text_from_bytes(pdf_bytes: bytes) -> str:
        text_pages = []
        try:
            reader = PdfReader(io.BytesIO(pdf_bytes))
            for page_num, page in enumerate(reader.pages):
                extracted = page.extract_text()
                if extracted:
                    text_pages.append(extracted.strip())
            return "\n\n".join(text_pages)
        except Exception as e:
            logger.error(f"Error extracting text from PDF bytes: {e}")
            return ""

    @classmethod
    def parse_notice_pdf(cls, pdf_bytes: bytes, original_title: str = "") -> Dict[str, Any]:
        raw_text = cls.extract_text_from_bytes(pdf_bytes)
        
        extracted_data = {
            'raw_text': raw_text[:8000],  # keep preview
            'dates': cls.extract_dates(raw_text),
            'vacancies': cls.extract_vacancies(raw_text),
            'fees': cls.extract_fees(raw_text),
            'age_limit': cls.extract_age_limit(raw_text),
            'eligibility': cls.extract_eligibility(raw_text),
            'summary': cls.generate_brief_summary(raw_text, original_title),
        }
        return extracted_data

    @staticmethod
    def extract_dates(text: str) -> List[Dict[str, str]]:
        """
        Extracts labelled dates from official notices using common government notice patterns.
        """
        date_patterns = [
            (r'(?:date\s+of\s+(?:computer\s+based\s+)?exam(?:ination)?|exam\s+date|cbt\s+date)[:\s-]+([^\n\r,.;]+)', 'Exam Date'),
            (r'(?:online\s+application\s+(?:starts?|from)|commencement\s+of\s+online\s+application)[:\s-]+([^\n\r,.;]+)', 'Application Start Date'),
            (r'(?:last\s+date\s+(?:for|of)\s+application|closing\s+date|last\s+date\s+for\s+submission)[:\s-]+([^\n\r,.;]+)', 'Application Last Date'),
            (r'(?:admit\s+card\s+(?:release\s+date|download|available\s+from))[:\s-]+([^\n\r,.;]+)', 'Admit Card Date'),
            (r'(?:declaration\s+of\s+result|result\s+date|date\s+of\s+result)[:\s-]+([^\n\r,.;]+)', 'Result Date'),
            (r'(?:answer\s+key\s+(?:release\s+date|available\s+from))[:\s-]+([^\n\r,.;]+)', 'Answer Key Date'),
        ]

        found_dates = []
        seen_labels = set()
        
        for regex, label in date_patterns:
            match = re.search(regex, text, re.IGNORECASE)
            if match and label not in seen_labels:
                val = match.group(1).strip()
                if len(val) < 60 and any(char.isdigit() for char in val):
                    found_dates.append({'label': label, 'value': val})
                    seen_labels.add(label)

        # Fallback date extraction if no labeled dates were matched
        if not found_dates:
            generic_dates = re.findall(r'\b(?:\d{1,2}[-/.](?:\d{1,2}|[A-Za-z]{3,9})[-/.]\d{2,4})\b', text)
            if generic_dates:
                found_dates.append({'label': 'Notification Date', 'value': generic_dates[0]})

        return found_dates

    @staticmethod
    def extract_vacancies(text: str) -> str:
        patterns = [
            r'(?:total\s+number\s+of\s+vacanc(?:ies|y)|total\s+vacanc(?:ies|y)|tentative\s+vacancies|no\.\s+of\s+posts?)[:\s-]+([0-9,]+(?:\s*posts?)?)',
            r'([0-9,]+)\s+tentative\s+vacancies',
            r'vacancies\s*:\s*([0-9,]+)',
        ]
        for pattern in patterns:
            match = re.search(pattern, text, re.IGNORECASE)
            if match:
                return match.group(1).strip()
        return "Not specified in the official notification."

    @staticmethod
    def extract_fees(text: str) -> str:
        patterns = [
            r'(?:application\s+fee|fee\s+payable|fee)[:\s-]+([^\n\r.;]+(?:(?:rs\.?|inr|rupees)[^\n\r.;]*|\b(?:nil|free|exempted)\b))',
            r'(?:rs\.?|inr)\s*([0-9]+(?:\s*/-)?\s*(?:for\s+[^\n\r.;]+)?)',
        ]
        for pattern in patterns:
            match = re.search(pattern, text, re.IGNORECASE)
            if match:
                val = match.group(1).strip()
                if len(val) < 100:
                    return val
        return "Refer to official notification details."

    @staticmethod
    def extract_age_limit(text: str) -> str:
        patterns = [
            r'(?:age\s+limit|prescribed\s+age|candidate\s+must\s+be)[:\s-]+([0-9]{2}\s*to\s*[0-9]{2}\s*years[^\n\r.;]*)',
            r'([0-9]{2}-[0-9]{2}\s*years\s*(?:as\s+on\s+[^\n\r.;]*)?)',
        ]
        for pattern in patterns:
            match = re.search(pattern, text, re.IGNORECASE)
            if match:
                return match.group(1).strip()
        return "As per official board rules (check official notification)."

    @staticmethod
    def extract_eligibility(text: str) -> str:
        patterns = [
            r'(?:essential\s+educational\s+qualification|educational\s+qualification|eligibility\s+criteria)[:\s-]+([^\n\r.;]+(?:degree|graduate|10th|12th|diploma|b\.e|b\.tech|master)[^\n\r.;]*)',
        ]
        for pattern in patterns:
            match = re.search(pattern, text, re.IGNORECASE)
            if match:
                return match.group(1).strip()
        return "Degree / Qualification as specified in the official notice."

    @staticmethod
    def generate_brief_summary(text: str, title: str) -> str:
        lines = [line.strip() for line in text.splitlines() if len(line.strip()) > 30]
        if lines:
            first_meaningful = " ".join(lines[:2])
            if len(first_meaningful) > 350:
                first_meaningful = first_meaningful[:347] + "..."
            return first_meaningful
        return f"Official government notification regarding {title}."
