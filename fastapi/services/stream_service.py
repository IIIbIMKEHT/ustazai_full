from langchain_openai import ChatOpenAI
from langchain_core.output_parsers import StrOutputParser
from constants import task_types
from constants.subject import get_subject_by_id
from helper.prompt_helper import get_prompt
from dotenv import load_dotenv, find_dotenv
from langchain.memory import ConversationBufferWindowMemory
from langchain.callbacks.streaming_aiter import AsyncIteratorCallbackHandler
from langchain.callbacks.streaming_stdout_final_only import FinalStreamingStdOutCallbackHandler
from langchain.schema import LLMResult
from langchain.agents import AgentType, initialize_agent
from typing import Any
import asyncio
import tiktoken
from langchain_core.prompts import ChatPromptTemplate
import html

load_dotenv(find_dotenv(), override=True)

# Инициализация модели
model = ChatOpenAI(
    model_name="gpt-4o-mini",
    temperature=0.7,
    max_tokens=4096, 
    top_p=0.8,
    streaming=True
)

parser = StrOutputParser()

async def getStream(
        class_level: str, 
        subject: int, 
        task_type: int, 
        topic: str = None, 
        is_kk: bool = True,
        qty: int = None, 
        level_test: str = None, 
        term: str = "1"
    ):
    # Устанавливаем таймаут в 30 секунд
    timeout_seconds = 30
    start_time = asyncio.get_event_loop().time()

    try:
        # Формируем промпт
        prompt = get_prompt(
            class_level=class_level, 
            subject_id=int(subject), 
            topic=topic, 
            task_type=int(task_type),
            is_kk=is_kk, 
            qty=int(qty), 
            level_test=level_test, 
            term=term
        )
        
        # Формируем сообщения
        messages = [
            {"role": "system", "content": "You are a helpful assistant."},
            {"role": "user", "content": prompt}
        ]
        
        # Асинхронный стриминг данных от модели
        async for chunk in model.astream(messages):
            current_time = asyncio.get_event_loop().time()
            # Если прошло больше 30 секунд, закрываем поток
            if current_time - start_time > timeout_seconds:
                yield "data: [DONE]\n\n"
                break
            content = chunk.content  # Получение контента из объекта AIMessageChunk
            content = html.escape(chunk.content) if content else ""
            
            yield f"data: {content}\n\n"
        # После завершения цикла отправляем финальное сообщение
        yield "data: [DONE]\n\n"
        
    except asyncio.CancelledError:
        print("Stream was cancelled.")
        yield "data: [DONE]\n\n"


