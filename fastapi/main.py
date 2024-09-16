import asyncio
import os
from dotenv import load_dotenv
from fastapi.middleware.cors import CORSMiddleware
from fastapi import FastAPI, HTTPException, Request
from fastapi.responses import FileResponse, JSONResponse
from fastapi.responses import StreamingResponse
from langchain.callbacks.streaming_aiter import AsyncIteratorCallbackHandler
from helper.prompt_helper import get_prompt
from models.material_model import MaterialRequest
from services.encryption_service import decrypt_data
from services.generate_pdf import generate_pdf
from services.generate_service import check_topic_validity, generate_material
import uvicorn
from services.stream_service import getStream
from services.generate_word import generate_doc
load_dotenv()
app = FastAPI()
# Разрешение всех источников (для разработки)
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Можно указать конкретные домены, например: ["http://localhost:3000", "http://example.com"]
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

@app.get("/stream_material/")
async def generate_material_endpoint(class_level: int, subject: str, topic: str, task_type: str, is_kk: bool, qty: int, term: str, token: str):
    encryptionKey = os.getenv('ENCRYPTION_KEY')
    decryptedData = decrypt_data(token, encryptionKey)
    print(f"Count is: {decryptedData}")
    if (int(decryptedData) == 0):
        return None
    return StreamingResponse(getStream(class_level=class_level, subject=subject, topic=topic, task_type=task_type,
                                 is_kk=is_kk, qty=qty, term=term), media_type="text/event-stream")




@app.post("/generate_material/")
async def generate_material_endpoint(request: MaterialRequest):
    is_valid = check_topic_validity(request.class_level, request.subject, request.topic)

    if not is_valid:
        return {"error": "The selected topic does not match the subject and class.", "valid": False}

    material = generate_material(request.class_level, request.subject, request.topic, request.task_type,
                                 is_kk=request.is_kk, qty=request.qty, term=request.term)
    wordLink = generate_doc(material)

    return {
        "message": f"Material generated successfully for {request.class_level} class in {request.subject} "
                   f"on topic {request.topic}.",
        "material": material,
        "wordLink": wordLink,
        "valid": True
    }


@app.post("/generate_doc/")
async def generate_document_endpoint(request: Request):
    # Получаем HTML-контент из POST-запроса
    data = await request.json()
    html_content = data.get("html_content")
    
    if not html_content:
        return JSONResponse(status_code=400, content={"error": "HTML content is missing"})

    # Генерация Word документа
    download_link = generate_doc(html_content)
    
    # Возвращаем ссылку на скачивание
    return JSONResponse(content={"download_link": download_link})

@app.get('/download/{filename}')
async def download_file(filename: str):

    # Получаем полный путь к файлу
    file_path = os.path.join("export_docs/", filename)

    # Проверяем, существует ли файл
    if not os.path.exists(file_path):
        raise HTTPException(status_code=404, detail="Файл не найден")

    # Возвращаем файл для скачивания
    return FileResponse(file_path, media_type='application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        filename=filename)


if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=9000)
