import os
from sklearn.metrics.pairwise import cosine_similarity
from langchain_community.document_loaders import UnstructuredWordDocumentLoader, PyMuPDFLoader
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain.chains import RetrievalQA
from langchain_core.vectorstores import VectorStoreRetriever
from langchain_openai import ChatOpenAI
from langchain_chroma import Chroma
from langchain_openai import OpenAIEmbeddings


class LangChainService:
    def __init__(self):
        # Инициализация модели встраивания и хранилища векторов
        self.embedding_model = OpenAIEmbeddings(model="text-embedding-3-large")
        self.embedding_model = OpenAIEmbeddings(model="text-embedding-ada-002")
        self.vector_store = Chroma(embedding_function=self.embedding_model, persist_directory='chroma_db')
        self.llm = ChatOpenAI(model_name="gpt-4o", temperature=0.1)
        self.text_splitter = RecursiveCharacterTextSplitter(chunk_size=1000, chunk_overlap=10)

    def add_document(self, file_path, grade, subject):
        """
        Добавляет документ в векторное хранилище из файла (PDF или DOCX).

        :param file_path: Путь к файлу.
        :param grade: Класс, например, "7".
        :param subject: Предмет, например, "Математика".
        """
        ext = os.path.splitext(file_path)[1].lower()

        if ext == '.pdf':
            loader = PyMuPDFLoader(file_path)
        elif ext == '.docx':
            loader = UnstructuredWordDocumentLoader(file_path)
        else:
            raise ValueError("Unsupported file type")

        documents = loader.load()
        texts = self.text_splitter.split_documents(documents)

        metadata = {"grade": grade, "subject": subject}
        for text in texts:
            text.metadata.update(metadata)

        self.vector_store.add_documents(texts)

    def get_rag_response(self, query, grade, subject, task_type):
        """
                Выполняет поиск по документам с фильтрацией по классу и предмету.

                :param task_type:
                :param query: Вопрос пользователя.
                :param grade: Класс, например, "7".
                :param subject: Предмет, например, "Математика".
                :return: Ответ на вопрос.
                """
        # Используем оператор $and для фильтрации по нескольким условиям
        filter_conditions = {
            "$and": [
                {"grade": {"$eq": grade}},
                {"subject": {"$eq": subject}}
            ]
        }

        retriever = VectorStoreRetriever(
            vectorstore=self.vector_store,
            search_type="similarity",
            search_kwargs={"k": 20, "filter": filter_conditions}
        )
        # Проверяем, есть ли результаты после фильтрации
        results = retriever.vectorstore.similarity_search(query=query, k=20, filter=filter_conditions)

        if not results:
            return f"Извините, но в базе данных нет информации для {grade} класса по предмету {subject}."

        # Выводим извлеченные документы для проверки
        for doc in results:
            print("Extracted Document Content:", doc.page_content)

        # Преобразуем запрос в векторное представление
        query_embedding = self.embedding_model.embed_query(query)

        # Поиск по смыслу в извлеченных документах
        relevant_texts = []
        for doc in results:
            doc_embedding = self.embedding_model.embed_documents([doc.page_content])[0]
            similarity = cosine_similarity([query_embedding], [doc_embedding])[0][0]
            if similarity > 0.4:  # Порог для определения релевантности
                relevant_texts.append(doc.page_content)

        if not relevant_texts:
            return f"Извините, но я не нашел информации на тему '{query}' для {grade} класса по предмету {subject}."

        # Если найдено упоминание темы, генерируем материал
        combined_text = " ".join(relevant_texts)[:1000]

        # Сформировать запрос в зависимости от типа задачи
        if task_type == "тест":
            prompt = f"Используя следующую информацию, создай 5 тестов с правильными ответами по теме: {query}"
        elif task_type == "план урока":
            prompt = f"Используя следующую информацию, создай план урока по теме: {query}."
        elif task_type == "пересказ":
            prompt = f"Используя следующую информацию, напиши короткий пересказ по теме: {query}."
        elif task_type == "викторина":
            prompt = f"Используя следующую информацию, создай викторину по теме: {query}."
        elif task_type == "интеллектуальная игра":
            prompt = f"Используя следующую информацию, создай интеллектуальную игру по теме: {query}."
        else:
            return ("Неизвестный тип задачи. Пожалуйста, выберите корректный тип задачи (тест, план урока, пересказ, "
                    "викторина, интеллектуальная игра).")
        qa_chain = RetrievalQA.from_chain_type(llm=self.llm, chain_type="stuff")
        return qa_chain.invoke({'query': prompt})

    def list_documents(self, grade, subject):
        # Используем оператор $and для комбинирования условий фильтрации
        filter_conditions = {
            "$and": [
                {"grade": {"$eq": grade}},
                {"subject": {"$eq": subject}}
            ]
        }

        # Выполняем поиск с фильтрацией
        results = self.vector_store.similarity_search(query="", k=5, filter=filter_conditions)

        # Выводим содержимое найденных документов
        for doc in results:
            print(doc.page_content)


# langchain_service = LangChainService()
# class QueryRequest(BaseModel):
#     query: str
#     grade: str
#     subject: str
#     task_type: str


# @app.post("/query/")
# async def query_document(request: QueryRequest):
#     try:
#         response = langchain_service.get_rag_response(query=request.query, grade=request.grade, subject=request.subject,
#                                                       task_type=request.task_type)
#         return {"response": response}
#     except Exception as e:
#         raise HTTPException(status_code=500, detail=str(e))


# @app.post("/upload_document/")
# async def upload_document(grade: str = Form(...), subject: str = Form(...), file: UploadFile = File(...)):
#     try:
#         # Убедитесь, что директория temp_files существует
#         os.makedirs('temp_files', exist_ok=True)
#
#         # Сохранение загруженного файла
#         file_location = f"temp_files/{file.filename}"
#         with open(file_location, "wb") as file_object:
#             file_object.write(file.file.read())
#
#         # Проверьте, существует ли файл и имеет ли правильный формат
#         if not os.path.isfile(file_location):
#             raise HTTPException(status_code=400, detail="Файл не был загружен корректно")
#
#         # Проверка типа файла
#         ext = os.path.splitext(file_location)[1].lower()
#         if ext not in ['.pdf', '.docx']:
#             os.remove(file_location)
#             raise HTTPException(status_code=400, detail="Unsupported file type")
#
#         # Добавление документа в базу
#         langchain_service.add_document(file_location, grade, subject)
#
#         # Удаление временного файла после обработки
#         os.remove(file_location)
#
#         return {"status": "Document added successfully"}
#     except Exception as e:
#         raise HTTPException(status_code=500, detail=str(e))
#
#
# @app.get("/list-documents")
# async def list_documents(grade: str = Form(...), subject: str = Form(...)):
#     langchain_service.list_documents(grade=grade, subject=subject)