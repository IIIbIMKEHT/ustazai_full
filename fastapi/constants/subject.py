def get_subject_by_id(subject_id: int) -> str:
    match subject_id:
        case 1:
            result = 'Алгебра'
        case 2:
            result = 'Английский язык'
        case 3:
            result = 'Астрономия'
        case 4:
            result = 'Биология'
        case 5:
            result = 'География'
        case 6:
            result = 'Геометрия'
        case 7:
            result = 'Информатика'
        case 8:
            result = 'История Казахстана'
        case 9:
            result = 'Всемирная История'
        case 10:
            result = 'Музыка'
        case 11:
            result = 'Рисование'
        case 12:
            result = 'Русский язык'
        case 13:
            result = 'Русская литература'
        case 14:
            result = 'Физика'
        case 15:
            result = 'Химия'
        case 16:
            result = 'Физическая культура'

    return result
