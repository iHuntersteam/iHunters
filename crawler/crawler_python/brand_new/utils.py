import re


def create_search_patterns(persons_dict):
    """
    На входе
    {1: ['python', 'Питон', 'Пайтон', 'Python'], 2: ['PHP', 'ПХП', 'пыхапе', 'похапе', 'PHP']}
    :param persons_dict:
    :return:
    """
    _search_patterns = {}
    for key, value in persons_dict.items():
        search_val = '|'.join(value)
        _search_patterns[key] = re.compile(r'\b{}\b'.format(search_val), re.IGNORECASE | re.MULTILINE)
    return _search_patterns


def rank_page(search_patterns, body_text):
    result = {}
    for pattern_name in search_patterns:
        rank = search_patterns[pattern_name].findall(body_text)
        result[pattern_name] = len(rank)
    return result
