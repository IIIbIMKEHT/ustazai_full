import os

import uvicorn
from dotenv import load_dotenv
from fastapi import FastAPI, HTTPException, Request, Depends
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import FileResponse, JSONResponse
from fastapi.responses import StreamingResponse
from sqlalchemy.orm import Session

from models.material_model import MaterialRequest
from models.token_data import TokenData
from services.check_count import get_user_by_email, get_db
from services.generate_service import check_topic_validity, generate_material
from services.generate_word import generate_doc
from services.jwt_service import create_jwt_token
from services.stream_service import getStream

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


@app.post("/token")
async def create_token(data: TokenData):
    token = create_jwt_token(email=data.email)
    return {"access_token": token, "token_type": "bearer"}


@app.get("/stream_material/")
async def generate_material_endpoint(class_level: int, subject: str, topic: str, task_type: str, is_kk: bool, qty: int,
                                     term: str, email: str, db: Session = Depends(get_db)):
    if not email:
        raise HTTPException(status_code=400, detail="Email doesn't exist")
    get_user_by_email(email=email, db=db)
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
