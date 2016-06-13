from datetime import datetime
from db_connect import CrawlerSitesConnector


sites_conn = CrawlerSitesConnector()

insert_many = []
# Lenta
for i in range(1, 1001):
    found_date = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    insert_many.append(('http://192.168.1.119/get_lenta/'+str(i), 2, found_date))
# RBC
for i in range(1, 1001):
    found_date = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    insert_many.append(('http://192.168.1.119/get_rbc/'+str(i), 3, found_date))
# RIA
for i in range(1, 1001):
    found_date = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    insert_many.append(('http://192.168.1.119/get_ria/'+str(i), 4, found_date))
sites_conn.save_stack(insert_many)