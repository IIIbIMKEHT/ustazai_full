from langchain_openai import ChatOpenAI
from constants import task_types
from constants.subject import get_subject_by_id
from helper.prompt_helper import get_prompt
from dotenv import load_dotenv, find_dotenv
import tiktoken
load_dotenv(find_dotenv(), override=True)

# chat_model = ChatOpenAI(model_name="gpt-4o-2024-08-06", temperature=0.6, max_tokens=4096, top_p=0.8)
chat_model = ChatOpenAI(model_name="gpt-4o-mini", temperature=0.6, max_tokens=4096, top_p=0.8)


def check_topic_validity(class_level: str, subject_id: int, topic: str) -> bool:
    subject = get_subject_by_id(subject_id)
    # Формируем запрос к модели
    prompt = (
        f"Соответствует ли тема '{topic}' учебной программе по предмету '{subject}' для учащихся '{class_level}' "
        f"класса в Казахстане? Ответьте только 'да' или 'нет'."
    )
    # print(prompt)

    # Формируем сообщения для чата
    messages = [
        {"role": "system", "content": "You are a helpful assistant."},
        {"role": "user", "content": prompt}
    ]

    # Выполняем запрос к модели
    response = chat_model.invoke(messages)

    # Извлекаем текст ответа
    answer = response.content.strip()
    print(answer)
    # Проверяем ответ модели
    return "да" in answer.lower()


def generate_material(class_level: str, subject: int, topic: str, task_type: int, is_kk: bool = True,
                      qty: int = None, level_test: str = None, term: str = "1") -> str:
    prompt = get_prompt(class_level=class_level, subject_id=subject, topic=topic, task_type=task_type,
                        is_kk=is_kk, qty=qty, level_test=level_test, term=term)
    
    messages = [
        {"role": "system", "content": "You are a helpful assistant."},
        {"role": "user", "content": prompt}
    ]
    
    response = chat_model.invoke(messages)
    # Извлечение фактического использования токенов из метаданных ответа
    total_tokens_response = response.response_metadata.get('token_usage', {}).get('total_tokens', 0)
    material = response.content.strip()
    getPrice(messages=messages, total_tokens=total_tokens_response)
    return material
    # return prompt

def getPrice(messages, total_tokens):
    # Пример расчета стоимости
    cost_per_million_tokens = 0.6
    total_cost = (total_tokens / 1000000) * cost_per_million_tokens
    print(f"Total tokens: {total_tokens}")
    print(f"Total cost: ${total_cost:.4f}")
