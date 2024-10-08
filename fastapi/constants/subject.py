def get_subject_by_id(subject_id: int, lang: str = 'ru') -> str:
    match subject_id:
        case 1:
            result = {'kk': 'Қазақ тілі', 'ru': 'Казахский язык'}
        case 2:
            result = {'kk': 'Орыс тілі', 'ru': 'Русский язык'}
        case 3:
            result = {'kk': 'Әдебиеттік оқу', 'ru': 'Литературное чтение'}
        case 4:
            result = {'kk': 'Математика', 'ru': 'Математика'}
        case 5:
            result = {'kk': 'Дүниетану', 'ru': 'Окружающий мир'}
        case 6:
            result = {'kk': 'Бейнелеу өнері', 'ru': 'Изобразительное искусство'}
        case 7:
            result = {'kk': 'Музыка', 'ru': 'Музыка'}
        case 8:
            result = {'kk': 'Еңбекке баулу', 'ru': 'Трудовое обучение'}
        case 9:
            result = {'kk': 'Дене шынықтыру', 'ru': 'Физическая культура'}
        case 10:
            result = {'kk': 'Өзін-өзі тану', 'ru': 'Самопознание'}
        case 11:
            result = {'kk': 'Қазақ тілі мен әдебиеті', 'ru': 'Казахский язык и литература'}
        case 12:
            result = {'kk': 'Орыс тілі мен әдебиеті', 'ru': 'Русский язык и литература'}
        case 13:
            result = {'kk': 'Ағылшын тілі', 'ru': 'Английский язык'}
        case 14:
            result = {'kk': 'Қазақстан тарихы', 'ru': 'История Казахстана'}
        case 15:
            result = {'kk': 'Дүниежүзі тарихы', 'ru': 'Всеобщая история'}
        case 16:
            result = {'kk': 'География', 'ru': 'География'}
        case 17:
            result = {'kk': 'Биология', 'ru': 'Биология'}
        case 18:
            result = {'kk': 'Физика', 'ru': 'Физика'}
        case 19:
            result = {'kk': 'Химия', 'ru': 'Химия'}
        case 20:
            result = {'kk': 'Информатика', 'ru': 'Информатика'}
        case 21:
            result = {'kk': 'Алгебра', 'ru': 'Алгебра'}
        case 22:
            result = {'kk': 'Геометрия', 'ru': 'Геометрия'}
        case 23:
            result = {'kk': 'Адам. Қоғам. Құқық', 'ru': 'Человек. Общество. Право'}
        case 24:
            result = {'kk': 'Физикалық мәдениет', 'ru': 'Физическая культура'}
        case 25:
            result = {'kk': 'Өзін-өзі тану', 'ru': 'Самопознание'}
        case _:
            result = {'kk': 'Предмет табылмады', 'ru': 'Предмет не найден'}

    return result.get(lang, 'Предмет не найден')