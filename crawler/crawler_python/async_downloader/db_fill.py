from datetime import datetime
from db_connect import CrawlerSitesConnector


sites_conn = CrawlerSitesConnector()

site_id = 1  # Geektimes
insert_many = []
for i in range(1, 401):
    found_date = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    insert_many.append(('http://192.168.1.119/10test_class_'+str(i), site_id, found_date))

sites_conn.save_stack(insert_many)