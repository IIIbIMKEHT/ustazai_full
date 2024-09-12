from constants.subject import get_subject_by_id
from .prompt_ru import task_templates as ru_task_templates
from .prompt_kk import task_templates as kk_task_templates
from constants.task_types import TaskTypes




# Определяем язык до использования в шаблоне
def determine_language(subject, language):
    if subject in ["Английский язык", "Русский язык"]:
        return subject
    return language


# Функция генерации промпта
def get_prompt(class_level: str, subject_id: int, task_type: int, topic: str = "", is_kk: bool = True, qty: int = None,
               level_test: str = None, term: str = "1"):
    subject = get_subject_by_id(subject_id)

    # Подготовка значений по умолчанию
    if is_kk is True:
        interface_lang = 'казахском'
    else:
        interface_lang = 'русском'

    lang = determine_language(subject, interface_lang)
    if level_test is None:
        level_test = 'средний'

    # Преобразование task_type в объект TaskTypes
    try:
        task_type_enum = TaskTypes(task_type)
    except ValueError:
        return "Неизвестный тип задачи. Пожалуйста, выберите корректный тип задачи."

    if is_kk is True:
        # Используем TaskTypes для выбора шаблона
        if task_type_enum.name in kk_task_templates:
            template = kk_task_templates[task_type_enum.name]
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
    else:
        # Используем TaskTypes для выбора шаблона
        if task_type_enum.name in ru_task_templates:
            template = ru_task_templates[task_type_enum.name]
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
    
