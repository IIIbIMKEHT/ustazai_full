from llama_index.core import VectorStoreIndex, SimpleDirectoryReader


def create_vector_embeddings(content: str):
    print('Loading document...')
    # Логика загрузки учебного материала в векторную базу данных
    document = SimpleDirectoryReader(input_dir=content).load_data()
    print('Loading vectors...')
    index = VectorStoreIndex.from_documents(document)
    index.storage_context.persist(persist_dir="./db")
    return index
