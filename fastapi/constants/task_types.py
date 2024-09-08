from enum import Enum


class TaskTypes(Enum):
    LESSON_PLAN = 7  # План урока
    TEST = 1  # Тест
    QUIZ = 2  # Викторина
    INTELLECTUAL_GAME = 3  # Интеллектуальная игра
    EXERCISES = 4  # Упражнения
    SUMMARY = 5  # Конспектный материал
    LABORATORY = 6  # Лабораторная работа
