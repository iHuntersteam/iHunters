import pymysql
from datetime import datetime

HOST = 'database_warner'
PORT = 3306
USER = 'ihunters'
PASSWORD = 'ihunterspass'
DBNAME = 'ihunters'
#
# HOST = '185.117.153.190'
# PORT = 5555
# USER = 'ihunters'
# PASSWORD = 'ihunterspass'
# DBNAME = 'ihunters'

# HOST = 'localhost'
# PORT = 3306
# USER = 'mysql'
# PASSWORD = 'mysql'
# DBNAME = 'ihunters'

CONN = pymysql.connect(host=HOST, port=PORT, user=USER,
                       password=PASSWORD, db=DBNAME,
                       use_unicode=True, charset='utf8',
                       autocommit=False)
CURSOR = CONN.cursor(pymysql.cursors.Cursor)


def address_gen(number_of_pages, site_id):
    for i in range(0, number_of_pages):
        found_date = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        url = 'http://example.com/{}'.format(i)
        yield (url, site_id, found_date)

start_time = datetime.now()
num_of_iterations = 3
num_of_pages = 10000
for _ in range(num_of_iterations):
    print('Старт нового прохода')
    CURSOR.execute('DELETE FROM pages')
    # CONN.commit()
    pages = address_gen(num_of_pages, 1)
    insert_many = []
    split_counter = 0
    for page in pages:
        insert_many.append(page)
        split_counter += 1
        if split_counter > 2000:
            CURSOR.executemany('INSERT INTO pages(url, site_id, found_date_time) VALUES(%s, %s, %s)', insert_many)
            CONN.commit()
            insert_many.clear()
            print('Вставлено 2к записей.')
            split_counter = 0
    CURSOR.executemany('INSERT INTO pages(url, site_id, found_date_time) VALUES(%s, %s, %s)', insert_many)
    CONN.commit()
print('------------------ Общее время -------------')
t = datetime.now() - start_time
print(t)
print('------------------ Среднее время -------------')
print(t/num_of_iterations)
print('------------------ Запросов в секунду -------------')
print((num_of_iterations * num_of_pages) / t.seconds)