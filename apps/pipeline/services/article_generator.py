import re
from typing import Dict, Any, Tuple
from apps.categories.models import Category
from apps.articles.models import ArticleTemplate

class ArticleGenerator:
    """
    Transforms extracted official government notices into clean,
    structured, highly readable news articles with strict fact-grounding.
    Never invents unverified data.
    """

    @classmethod
    def classify_category_and_template(cls, title: str, text: str = "") -> Tuple[str, str]:
        """
        Classifies incoming notice into Category slug and Template Type.
        """
        combined = (title + " " + text[:500]).lower()

        if re.search(r'\b(result|marks|score card|merit list|cut off|selected candidates)\b', combined):
            return 'results', 'result'
        elif re.search(r'\b(admit card|hall ticket|call letter|city intimation|entry pass)\b', combined):
            return 'admit-card', 'admit_card'
        elif re.search(r'\b(recruitment|vacancy|vacancies|posts|job notification|application form|apply online|direct recruitment)\b', combined):
            return 'recruitment', 'recruitment'
        elif re.search(r'\b(answer key|response sheet|objection tracker|provisional key)\b', combined):
            return 'answer-key', 'answer_key'
        elif re.search(r'\b(exam date|exam schedule|time table|datesheet|cbt schedule)\b', combined):
            return 'exam-date', 'exam'
        elif re.search(r'\b(scholarship|fellowship|financial aid)\b', combined):
            return 'scholarship', 'general_news'
        elif re.search(r'\b(admission|counselling|seat allotment|entrance)\b', combined):
            return 'admission', 'general_news'
        else:
            return 'important-updates', 'general_news'

    @classmethod
    def generate_article_payload(
        cls, 
        raw_title: str, 
        official_url: str, 
        source_name: str, 
        official_domain: str, 
        parsed_pdf_data: Dict[str, Any],
        official_pdf_url: str = ""
    ) -> Dict[str, Any]:
        """
        Builds the complete structured article dictionary for saving into the Article model.
        """
        cat_slug, template_type = cls.classify_category_and_template(raw_title, parsed_pdf_data.get('raw_text', ''))
        
        # Clean headline
        clean_headline = cls._format_headline(raw_title, source_name)
        
        # Extract or populate structured fields
        authority = cls._extract_authority(raw_title, source_name)
        exam_name = cls._extract_exam_name(raw_title)
        
        dates_list = parsed_pdf_data.get('dates', [])
        if not dates_list:
            dates_list = [{'label': 'Notification Released', 'value': 'Check official notice'}]
            
        vacancies = parsed_pdf_data.get('vacancies', 'Not specified in the official notification.')
        fees = parsed_pdf_data.get('fees', 'Refer to official notification details.')
        age_limit = parsed_pdf_data.get('age_limit', 'As per official recruitment rules.')
        eligibility = parsed_pdf_data.get('eligibility', 'Check official notification.')
        
        # Generate Steps
        steps = cls._generate_steps(template_type, authority, exam_name)
        
        # Generate Important Links
        important_links = [
            {'title': f'Official {authority} Website', 'url': f'https://{official_domain}' if not official_domain.startswith('http') else official_domain, 'is_primary': False},
            {'title': 'Official Source Notice Link', 'url': official_url, 'is_primary': True},
        ]
        if official_pdf_url:
            important_links.append({'title': 'Download Official Notification PDF', 'url': official_pdf_url, 'is_primary': True})
            
        # Generate FAQs
        faqs = cls._generate_faqs(template_type, authority, exam_name, dates_list, official_domain)
        
        # Structured Data JSON Store
        structured_data = {
            'authority': authority,
            'exam_name': exam_name,
            'vacancies': vacancies,
            'fees': fees,
            'age_limit': age_limit,
            'eligibility': eligibility,
            'dates': dates_list,
            'steps': steps,
            'important_links': important_links,
            'faq': faqs,
            'official_source_name': source_name,
            'official_source_url': official_url,
            'official_pdf_url': official_pdf_url,
        }
        
        # Build Semantic HTML Body
        content_html = cls._build_html_content(
            template_type=template_type,
            headline=clean_headline,
            authority=authority,
            exam_name=exam_name,
            structured_data=structured_data,
            summary=parsed_pdf_data.get('summary', '')
        )
        
        excerpt = parsed_pdf_data.get('summary') or f"{authority} has officially published notice regarding {clean_headline}. Check important dates, eligibility, direct download links and official updates."
        if len(excerpt) > 400:
            excerpt = excerpt[:397] + "..."

        return {
            'title': clean_headline,
            'category_slug': cat_slug,
            'template_type': template_type,
            'excerpt': excerpt,
            'content_html': content_html,
            'structured_data': structured_data,
            'official_url': official_url,
            'official_pdf_url': official_pdf_url,
        }

    @staticmethod
    def _format_headline(title: str, source_name: str) -> str:
        cleaned = title.strip()
        # Ensure clean capitalization if ALL CAPS
        if cleaned.isupper() and len(cleaned) > 20:
            cleaned = cleaned.title()
        return cleaned

    @staticmethod
    def _extract_authority(title: str, source_name: str) -> str:
        for auth in ['SSC', 'UPSC', 'NTA', 'RRB', 'RRC', 'IBPS', 'CBSE', 'BPSC', 'UPPSC', 'KVS', 'DSSSB']:
            if auth.lower() in (title + " " + source_name).lower():
                return auth
        return source_name.split()[0] if source_name else "Official Authority"

    @staticmethod
    def _extract_exam_name(title: str) -> str:
        # Match common exam naming patterns
        match = re.search(r'([A-Za-z0-9\s\-]+(?:Exam|Examination|Recruitment|CGL|CHSL|MTS|GD|JE|NDA|CDS|CSE|NEET|JEE|CUET|NTPC|Group D|ALP)[\w\s\-]*(?:202[4-9]|2030)?)', title, re.IGNORECASE)
        if match:
            return match.group(1).strip()
        return title[:50].strip()

    @staticmethod
    def _generate_steps(template_type: str, authority: str, exam_name: str) -> list:
        if template_type == 'result':
            return [
                f"Visit the official website of {authority}.",
                "Navigate to the 'Results / Latest Updates' section on the homepage.",
                f"Click on the link for '{exam_name} Result / Merit List'.",
                "Enter your Roll Number, Registration Number, and Date of Birth / Password if prompted.",
                "Download the PDF result file or view your scorecard on the screen.",
                "Save and print a copy of the result for future reference."
            ]
        elif template_type == 'admit_card':
            return [
                f"Go to the official portal of {authority}.",
                "Find the 'Admit Card / Hall Ticket' download link on the notices board.",
                f"Select the link for '{exam_name} Admit Card'.",
                "Log in using your Application Number and Date of Birth / Password.",
                "Check all details on the Admit Card including Exam Center, Shift Timing, and Instructions.",
                "Download and print multiple hard copies to bring to the examination hall."
            ]
        elif template_type == 'recruitment':
            return [
                f"Visit the official recruitment portal of {authority}.",
                "Read the official notification PDF thoroughly to verify eligibility criteria.",
                "Click on 'New Registration' or log in with your existing credentials.",
                "Fill in all personal, educational, and communication details accurately.",
                "Upload required scanned documents (photograph, signature, certificates) as per specifications.",
                "Pay the prescribed application fee through online payment gateway (if applicable).",
                "Submit the application and save the confirmation page / registration slip."
            ]
        else:
            return [
                f"Visit the official website of {authority}.",
                "Open the 'Latest Announcements / Notice Board' section.",
                f"Locate and click on the official notice regarding '{exam_name}'.",
                "Download and review the official PDF document carefully for complete details."
            ]

    @staticmethod
    def _generate_faqs(template_type: str, authority: str, exam_name: str, dates: list, domain: str) -> list:
        exam_or_notice = exam_name if exam_name else "the examination"
        
        faq_list = [
            {
                'question': f"What is the official website for {authority} {exam_or_notice} updates?",
                'answer': f"The official website is {domain}. Candidates should always rely strictly on official portals for verified information."
            }
        ]

        if template_type == 'result':
            faq_list.append({
                'question': f"How can I check my {exam_or_notice} result?",
                'answer': f"Candidates can check the result by visiting {domain}, locating the result link, and checking their roll number in the official result merit list or logging in with credentials."
            })
            faq_list.append({
                'question': f"What details are required to check {exam_or_notice} score?",
                'answer': "Usually, Roll Number / Registration Number along with Date of Birth or Password are required."
            })
        elif template_type == 'admit_card':
            faq_list.append({
                'question': f"Is the {exam_or_notice} Admit Card released?",
                'answer': f"Please refer to the Important Dates table above and check the official download link provided directly from {domain}."
            })
            faq_list.append({
                'question': "What documents should I carry to the exam center along with the Admit Card?",
                'answer': "Candidates must carry a printed copy of the Admit Card, original valid photo ID proof (Aadhaar Card, PAN Card, Voter ID, Passport), and recent passport-size photographs."
            })
        elif template_type == 'recruitment':
            faq_list.append({
                'question': f"Where can I apply for {authority} {exam_or_notice}?",
                'answer': f"Applications must be submitted online exclusively through the official website {domain} before the last date."
            })
            faq_list.append({
                'question': "Where can I find the official notification PDF?",
                'answer': "The direct official PDF link is provided in the Important Links table on this page."
            })

        return faq_list

    @classmethod
    def _build_html_content(
        cls, 
        template_type: str, 
        headline: str, 
        authority: str, 
        exam_name: str, 
        structured_data: Dict[str, Any],
        summary: str
    ) -> str:
        """
        Generates semantic, accessible HTML for the article.
        """
        dates_rows = "".join([
            f"<tr><td class='font-medium'>{d.get('label', 'Date')}</td><td class='text-navy-900'>{d.get('value', 'Check Notice')}</td></tr>"
            for d in structured_data.get('dates', [])
        ])

        steps_items = "".join([
            f"<li>{step}</li>"
            for step in structured_data.get('steps', [])
        ])

        links_rows = "".join([
            f"<tr><td>{link.get('title')}</td><td><a href='{link.get('url')}' target='_blank' rel='noopener noreferrer nofollow' class='link-btn'>{'Click Here ↗' if link.get('is_primary') else 'Visit Website ↗'}</a></td></tr>"
            for link in structured_data.get('important_links', [])
        ])

        faq_items = "".join([
            f"<details class='faq-item'><summary class='faq-question'><strong>{faq.get('question')}</strong></summary><div class='faq-answer'><p>{faq.get('answer')}</p></div></details>"
            for faq in structured_data.get('faq', [])
        ])

        # Recruitment specific block
        recruitment_details_html = ""
        if template_type == 'recruitment':
            recruitment_details_html = f"""
            <div class='info-box mb-6'>
                <h3 class='section-heading'>Eligibility & Vacancy Details</h3>
                <div class='table-responsive'>
                    <table class='data-table'>
                        <tbody>
                            <tr><td><strong>Total Vacancies</strong></td><td>{structured_data.get('vacancies')}</td></tr>
                            <tr><td><strong>Eligibility Criteria</strong></td><td>{structured_data.get('eligibility')}</td></tr>
                            <tr><td><strong>Age Limit</strong></td><td>{structured_data.get('age_limit')}</td></tr>
                            <tr><td><strong>Application Fee</strong></td><td>{structured_data.get('fees')}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            """

        html = f"""
        <div class="article-content-body">
            <div class="lead-summary">
                <p>{summary if summary else f'{authority} has issued an official notice regarding <strong>{headline}</strong>. Candidates can check all official dates, key requirements, step-by-step instructions, and direct links below.'}</p>
            </div>

            <!-- Important Dates Table -->
            <div class="dates-container mb-6">
                <h3 class="section-heading">Important Dates & Schedule</h3>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Event / Activity</th>
                                <th>Date / Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {dates_rows}
                        </tbody>
                    </table>
                </div>
            </div>

            {recruitment_details_html}

            <!-- Step by Step Guide -->
            <div class="steps-container mb-6">
                <h3 class="section-heading">How to Check / Apply / Download</h3>
                <ol class="step-list">
                    {steps_items}
                </ol>
            </div>

            <!-- Important Links Table -->
            <div class="links-container mb-6">
                <h3 class="section-heading">Important Direct Links</h3>
                <div class="table-responsive">
                    <table class="data-table links-table">
                        <thead>
                            <tr>
                                <th>Resource / Action</th>
                                <th>Direct Link</th>
                            </tr>
                        </thead>
                        <tbody>
                            {links_rows}
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Frequently Asked Questions -->
            <div class="faq-container mb-6">
                <h3 class="section-heading">Frequently Asked Questions (FAQs)</h3>
                <div class="faq-list">
                    {faq_items}
                </div>
            </div>

            <!-- Official Source Disclaimer -->
            <div class="source-verification-box">
                <div class="source-icon">🏛️</div>
                <div class="source-info">
                    <strong>Official Source Verification:</strong>
                    <p>This information is aggregated directly from public updates published by <strong>{authority}</strong> ({structured_data.get('official_source_name')}). Candidates are advised to verify details from the official link: <a href="{structured_data.get('official_source_url')}" target="_blank" rel="noopener noreferrer nofollow" class="underline text-blue-800">{structured_data.get('official_source_url')}</a>.</p>
                </div>
            </div>
        </div>
        """
        return html.strip()
