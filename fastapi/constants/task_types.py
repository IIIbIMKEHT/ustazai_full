from enum import Enum


class TaskTypes(Enum):
    TEST = 1  # Тест
    QUIZ = 2  # Викторина
    INTELLECTUAL_GAME = 3  # Интеллектуальная игра
    EXERCISES = 4  # Упражнения
    SUMMARY = 5  # Конспектный материал
    LABORATORY = 6  # Лабораторная работа
    LESSON_PLAN = 7  # План урока
    SOR = 8  # СОР (Суммативное оценивание за раздел)
    SOCH = 9  # СОЧ (Суммативное оценивание за четверть)
    KSP = 10  # КСП (краткосрочный план урока)
