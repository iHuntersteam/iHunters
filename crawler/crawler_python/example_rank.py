from parse import HTMLParser
# в id текст вписал. чтобы нагляднее было
persons_dict = {'Путин': ('путин', 'путину', 'путина', 'путине'), 'Муров': ('муров', 'мурову', 'мурове')}
parser = HTMLParser(persons_dict)
pages_dict = {1: 'https://lenta.ru/news/2016/05/27/yaroshenko/', 2: 'https://lenta.ru/news/2016/05/26/usprivat/',
              3: 'https://lenta.ru/articles/2016/05/26/murov/', 4: 'http://localhost/index.html',
              5: 'https://geekbrains.ru/'}
# страница 4 специально добавлена для ошибки. её не удаётся скачать, поэтому в результате выводится 4: {} - пустой
# словарь. Страница 5 загружается, но там не слова про персон, поэтому нули возвращаются.
results = parser.get_info_from(pages_dict)
print(results)