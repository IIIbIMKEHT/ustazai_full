from constants.subject import get_subject_by_id
from .prompt_ru import task_templates as ru_task_templates
from .prompt_kk import task_templates as kk_task_templates
from constants.task_types import TaskTypes




# Определяем язык до использования в шаблоне
def determine_language(subject, language):
    if subject in ["Английский язык", "Русский язык", "Ағылшын тілі", "Орыс тілі", "Қазақ тілі", "Казахский язык"]:
        return subject
    return language


# Функция генерации промпта
def get_prompt(
        class_level: str, 
        subject_id: int, 
        task_type: int, 
        topic: str = "", 
        is_kk: bool = True, 
        qty: int = None,
        level_test: str = None, 
        term: str = "1"
    ):
    print(f"SubjectID is: {type(subject_id)}")
    language = 'kk' if is_kk else 'ru'
    # Получаем предмет по его идентификатору
    subject = get_subject_by_id(subject_id=subject_id, lang=language)
    print(f"Subject is: {subject}")
    # Подготовка значений по умолчанию для языка интерфейса
    interface_lang = 'казахском' if is_kk else 'русском'
    
    # Определение языка контента
    lang = determine_language(subject, interface_lang)
    
    # Установка уровня теста по умолчанию
    if level_test is None:
        level_test = 'средний'

    # Преобразование task_type в объект TaskTypes
    try:
        task_type_enum = TaskTypes(task_type)
    except ValueError:
        return "Неизвестный тип задачи. Пожалуйста, выберите корректный тип задачи."

    # Выбор правильного шаблона в зависимости от языка интерфейса
    task_templates = kk_task_templates if is_kk else ru_task_templates
    
    # Проверка, существует ли шаблон для данного task_type
    if task_type_enum.name in task_templates:
        template = task_templates[task_type_enum.name]
        return template.format(
            class_level=class_level,
            subject=subject,
            topic=topic,
            qty=qty,
            level_test=level_test,
            language=lang,
            term=term
        )
    else:
        return "Неизвестный тип задачи. Пожалуйста, выберите корректный тип задачи."
    
