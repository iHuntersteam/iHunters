import time
from rank_worker import PageRankWorker
from sitemap_worker import SitemapWorker
from db_connect import CURSOR, MySQLError, err


def get_count_from_db():
    try:
        CURSOR.execute('''
        SELECT ( SELECT COUNT(*) FROM sites ) AS 'sites_count',
               ( SELECT COUNT(*) FROM keywords ) AS 'keywords_count',
               ( SELECT COUNT(*) FROM pages WHERE last_scan_date IS NULL ) AS 'pages_count'
        ''')
        return CURSOR.fetchone()
    except MySQLError as e:
        print(err(e))
        return


if __name__ == '__main__':
    try:
        with open('counters.txt') as counters_txt:
            sites_count_old, keywords_count_old = map(int, counters_txt.readline().split(','))
    except FileNotFoundError:
        with open('counters.txt', mode='w') as counters_txt:
            counters_txt.write('0, 0')
        sites_count_old, keywords_count_old = 0, 0

    rescan_base_safe_counter = 0

    while True:
        print('Starting new loop. It\'ll start in 60 seconds.')
        time.sleep(6)
        db_data = get_count_from_db()
        if db_data:
            sites_count, keywords_count, unscanned_pages_count = get_count_from_db()
            print('Number of sites: {}, keywords: {}. Pages unscanned: {}'.format(sites_count, keywords_count, unscanned_pages_count))
            # is there new websites?
            if sites_count > sites_count_old:
                print('New website detected. Parsing sitemaps.')
                worker = SitemapWorker()
                worker.crawl_sitemaps()
                sites_count_old = sites_count
                print('Write to file new sites count.')
                with open('counters.txt', mode='w') as counters_txt:
                    counters_txt.write('{}, {}'.format(sites_count, keywords_count_old))
            # is there new keywords
            if keywords_count > keywords_count_old:
                print('New keywords detected. If this changes will remain after 5 minutes - '
                      'the base are going to be rescanned')
                if rescan_base_safe_counter < 5:
                    print('The #{} probe'.format(rescan_base_safe_counter))
                    rescan_base_safe_counter += 1
                    continue
                else:
                    rescan_base_safe_counter = 0
                    try:
                        CURSOR.execute('''UPDATE pages SET last_scan_date=NULL''')
                    except MySQLError as e:
                        print(err(e))
                        continue
                    print('Write to file new keywords count and starting a crawler.')
                    with open('counters.txt', mode='w') as counters_txt:
                        counters_txt.write('{}, {}'.format(sites_count, keywords_count))
                    test = PageRankWorker()
                    test.crawl_all()
            elif keywords_count < keywords_count_old:
                keywords_count_old = keywords_count
            else:
                rescan_base_safe_counter = 0

            if unscanned_pages_count:
                print('Found unscanned pages. Starting crawler')
                test = PageRankWorker()
                test.crawl_all()
