# from brand_new.celery_app import app
#
# app.control.broadcast('rate_limit',
#                       arguments={'task_name': 'brand_new.crawler_tasks.WebCrawler1PerSec',
#                                  'rate_limit': '{}/s'.format(200)})

from brand_new.db_connect import CrawlerSitesConnector
test = CrawlerSitesConnector()
test.save('http://192.168.1.119/get_lenta/500', 2)


# from itertools import islice
# def geeen(letter, count):
#     for i in range(1, count+1):
#         yield '{}{}'.format(letter, i)
#
# gen1 = geeen('A', 10)
# gen2 = geeen('B', 21)
# gen3 = geeen('C', 5)
#
# gen_list = [gen1, gen2, gen3]
#
# while len(gen_list) > 0:
#     for index, g in enumerate(gen_list):
#         if index == 0:
#             sep = 5
#         elif index == 1:
#             sep = 10
#         else:
#             sep = 1
#         ret = list(islice(g, sep))
#         if len(ret) == 0:
#             del gen_list[index]
#         for item in list(ret):
#             print('Got: {}'.format(item))
#     print('----------------')

unsorted = [('site1', 5, 1000), ('site2', 3, 1000), ('site3', 1, 1000)]
srt = sorted(unsorted, key=lambda x: x[2]//x[1])
print(srt)