from parse import HTMLParser
from datetime import datetime
# в id текст вписал. чтобы нагляднее было
persons_dict = {'Путин': ('путин', 'путину', 'путина', 'путине'), 'Муров': ('муров', 'мурову', 'мурове')}
parser = HTMLParser(persons_dict)

pages_dict = {1: ('https://lenta.ru/news/2016/05/27/yaroshenko/', datetime.now().strftime('%Y-%m-%d %H:%M:%S')),
              2: ('https://lenta.ru/news/2016/05/26/usprivat/', '2016-05-26 15:11:05'),
              3: ('https://lenta.ru/articles/2016/05/26/murov/', '2016-05-26 10:10:15'),
              4: ('http://localhost/index.html', '2016-05-30 21:41:15'),
              5: ('https://geekbrains.ru/', '2016-05-30 21:41:15')}
# страница 4 специально добавлена для ошибки. её не удаётся скачать, поэтому в результате выводится 4: {} - пустой
# словарь. Страница 5 загружается, но там не слова про персон, поэтому нули возвращаются.
# А в первой странице дата ставится сегодняшняя, но сервер возвращает заголовок Last-modified, поэтому в результате
# с рейтингом возвращается правильная дата.
results = parser.get_info_from(pages_dict)
print(results)