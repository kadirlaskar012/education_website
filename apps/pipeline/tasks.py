import logging
from celery import shared_task
from django.utils import timezone
from apps.sources.models import Source, FetchLog, ProcessingLog
from apps.pipeline.services.pipeline_runner import PipelineRunner

logger = logging.getLogger(__name__)

@shared_task(name='apps.pipeline.tasks.fetch_all_sources_task')
def fetch_all_sources_task():
    """
    Celery Beat periodic task: Scrapes all active government education sources.
    """
    logger.info("Starting automated Celery task: fetch_all_sources_task")
    runner = PipelineRunner()
    results = runner.run_all_active_sources()
    logger.info(f"Finished fetch_all_sources_task: processed {len(results)} sources.")
    return results

@shared_task(name='apps.pipeline.tasks.fetch_single_source_task')
def fetch_single_source_task(source_id: int):
    """
    Asynchronous task to run a single source.
    """
    try:
        source = Source.objects.get(pk=source_id)
        runner = PipelineRunner()
        return runner.run_single_source(source)
    except Source.DoesNotExist:
        logger.error(f"Source with id {source_id} does not exist.")
        return {'error': f'Source {source_id} not found'}

@shared_task(name='apps.pipeline.tasks.cleanup_logs_task')
def cleanup_logs_task():
    """
    Daily maintenance task to clean up old fetch logs older than 30 days.
    """
    cutoff = timezone.now() - timezone.timedelta(days=30)
    deleted_fetch, _ = FetchLog.objects.filter(created_at__lt=cutoff).delete()
    deleted_proc, _ = ProcessingLog.objects.filter(created_at__lt=cutoff).delete()
    logger.info(f"Cleaned up {deleted_fetch} fetch logs and {deleted_proc} processing logs older than 30 days.")
    return {'deleted_fetch_logs': deleted_fetch, 'deleted_processing_logs': deleted_proc}
