from django.core.management.base import BaseCommand
from apps.sources.models import Source
from apps.pipeline.adapters.registry import get_adapter_instance, ADAPTER_REGISTRY

class Command(BaseCommand):
    help = "Tests a specific scraper adapter by name (ssc, upsc, nta, railway, generic_rss)"

    def add_arguments(self, parser):
        parser.add_argument('adapter_name', type=str, help='Name of adapter (ssc, upsc, nta, railway, generic_rss)')

    def handle(self, *args, **options):
        name = options['adapter_name'].lower()
        if name not in ADAPTER_REGISTRY:
            self.stdout.write(self.style.ERROR(f"Unknown adapter '{name}'. Available: {list(ADAPTER_REGISTRY.keys())}"))
            return

        mock_source = Source(
            name=f"Test {name.upper()} Source",
            url="https://example.gov.in",
            official_domain="example.gov.in",
            parser=name,
            source_type='html'
        )

        adapter = get_adapter_instance(mock_source)
        self.stdout.write(f"Testing adapter '{name}'...")
        items = adapter.fetch_items()
        self.stdout.write(self.style.SUCCESS(f"Successfully fetched {len(items)} items:"))
        for i, itm in enumerate(items, 1):
            self.stdout.write(f"  {i}. [{itm.date_str or 'No date'}] {itm.title}")
            if itm.pdf_url:
                self.stdout.write(f"     PDF: {itm.pdf_url}")
