import os

from fastapi import FastAPI, HTTPException
from starlette.responses import FileResponse

from models.material_model import MaterialRequest
from services.generate_pdf import generate_pdf
from services.generate_service import check_topic_validity, generate_material
import uvicorn

from services.generate_word import generate_doc

app = FastAPI()


@app.post("/generate_material/")
async def generate_material_endpoint(request: MaterialRequest):
    is_valid = check_topic_validity(request.class_level, request.subject, request.topic)

    if not is_valid:
        return {"error": "The selected topic does not match the subject and class.", "valid": False}

    material = generate_material(request.class_level, request.subject, request.topic, request.task_type,
                                 is_kk=request.is_kk, qty=request.qty)
    wordLink = generate_doc(material)

    return {
        "message": f"Material generated successfully for {request.class_level} class in {request.subject} "
                   f"on topic {request.topic}.",
        "material": material,
        "wordLink": wordLink,
        "valid": True
    }


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
