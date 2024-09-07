import chromadb
from langchain_openai import OpenAIEmbeddings


class ChromaDBService:
    def __init__(self):
        # Настройка подключения к ChromaDB
        self.client = chromadb.Client()
        self.collection = self.client.create_collection(name="documents")
        self.embedding_model = OpenAIEmbeddings(model="text-embedding-ada-002")

    def add_document(self, doc_id, content):
        embedding = self.embedding_model.embed(content)
        self.collection.add(doc_id, embedding, content)

    def query(self, query_text, top_k=5):
        embedding = self.embedding_model.embed(query_text)
        results = self.collection.query(embedding, top_k=top_k)
        return results
