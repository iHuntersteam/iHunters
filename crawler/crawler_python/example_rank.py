from parse import HTMLParser
# в id текст вписал. чтобы нагляднее было
persons_dict = {'Путин': ('путин', 'путину', 'путина', 'путине'), 'Муров': ('муров', 'мурову', 'мурове')}
parser = HTMLParser(persons_dict)
pages_dict = {1: 'https://lenta.ru/news/2016/05/27/yaroshenko/', 2: 'https://lenta.ru/news/2016/05/26/usprivat/', 3: 'https://lenta.ru/articles/2016/05/26/murov/'}
results = parser.get_info_from(pages_dict)
print(results)