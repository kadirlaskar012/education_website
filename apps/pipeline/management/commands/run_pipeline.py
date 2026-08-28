from django.core.management.base import BaseCommand
from apps.sources.models import Source
from apps.pipeline.services.pipeline_runner import PipelineRunner

class Command(BaseCommand):
    help = "Executes the automated education scraper and news publishing pipeline"

    def add_arguments(self, parser):
        parser.add_argument('--source', type=int, help='Run for a specific Source ID')
        parser.add_argument('--all', action='store_true', help='Run for all active sources')

    def handle(self, *args, **options):
        runner = PipelineRunner()
        source_id = options.get('source')

        if source_id:
            try:
                source = Source.objects.get(pk=source_id)
                self.stdout.write(f"Executing pipeline for source: {source.name}...")
                res = runner.run_single_source(source)
                self.stdout.write(self.style.SUCCESS(f"Result: {res}"))
            except Source.DoesNotExist:
                self.stdout.write(self.style.ERROR(f"Source with ID {source_id} not found."))
        else:
            self.stdout.write("Executing pipeline across all active sources...")
            results = runner.run_all_active_sources()
            for r in results:
                self.stdout.write(self.style.SUCCESS(f"Source '{r.get('source')}': {r}"))
