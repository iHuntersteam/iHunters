from celery import Celery
from brand_new.db_settings import (RABBITMQ_HOST, RABBITMQ_PASSWORD, RABBITMQ_PORT,
                                   RABBITMQ_USER, RABBITMQ_VIRTUAL_HOST)

rabbit_mq = 'amqp://{user}:{password}@{address}:{port}/{virthost}'.format(
            user=RABBITMQ_USER, password=RABBITMQ_PASSWORD, address=RABBITMQ_HOST,
            port=RABBITMQ_PORT, virthost=RABBITMQ_VIRTUAL_HOST)

app = Celery('tasks', broker=rabbit_mq)
app.conf.update(
    # CELERY_RESULT_BACKEND=redis_backend,
    # CELERY_RESULT_BACKEND='rpc://',
    CELERY_TASK_RESULT_EXPIRES=86400,
    # CELERY_ALWAYS_EAGER=True - Для дебага, запускает все задачи последовательно.
)