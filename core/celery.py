import os
from celery import Celery
from celery.schedules import crontab

# Set the default Django settings module for the 'celery' program.
os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'core.settings')

app = Celery('education_news')

# Using a string here means the worker doesn't have to serialize
# the configuration object to child processes.
app.config_from_object('django.conf:settings', namespace='CELERY')

# Load task modules from all registered Django apps.
app.autodiscover_tasks()

# Celery Beat Periodic Task Schedules
app.conf.beat_schedule = {
    'fetch-all-education-sources-every-15-min': {
        'task': 'apps.pipeline.tasks.fetch_all_sources_task',
        'schedule': crontab(minute='*/15'),  # Run every 15 minutes
    },
    'cleanup-old-logs-daily': {
        'task': 'apps.pipeline.tasks.cleanup_logs_task',
        'schedule': crontab(hour=2, minute=0),  # Run daily at 2:00 AM
    },
}

@app.task(bind=True, ignore_result=True)
def debug_task(self):
    print(f'Request: {self.request!r}')
