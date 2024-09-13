from langchain_openai import ChatOpenAI
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
load_dotenv(find_dotenv(), override=True)

llm = ChatOpenAI(
    model_name="gpt-4o-mini", 
    temperature=0.6, 
    max_tokens=4096, 
    top_p=0.8,
    streaming=True,
    callbacks=[]
)

# Инициализация памяти
memory = ConversationBufferWindowMemory(memory_key="chat_history", return_messages=True)

agent = initialize_agent(
    agent=AgentType.CHAT_CONVERSATIONAL_REACT_DESCRIPTION,
    tools=[],
    llm=llm,
    verbose=True,
    max_iterations=3,
    early_stopping_method="generate",
    return_intermediate_steps=False,
    memory=memory
)

async def run_call(query: str, stream_it: AsyncIteratorCallbackHandler):
    agent.agent.llm_chain.llm.callbacks = [stream_it]
    response = await agent.ainvoke({"input": query})
    return response

async def create_gen(query: str, stream_it: AsyncIteratorCallbackHandler):
    task = asyncio.create_task(run_call(query, stream_it))
    async for token in stream_it.aiter():
        print(f"Generated token: {token}")  # Вывод токенов в консоль
        yield token
    await task



