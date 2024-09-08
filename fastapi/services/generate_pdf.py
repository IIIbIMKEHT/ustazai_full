# Путь, где будут храниться документы
import os
import random
import string
from io import BytesIO

from docx import Document
from fastapi import HTTPException
from html2image import Html2Image
from htmldocx import HtmlToDocx
from reportlab.lib.pagesizes import letter
from reportlab.pdfgen import canvas
from xhtml2pdf import pisa

UPLOAD_FOLDER = 'export_docs/'
IMAGE_FOLDER = 'images/'


# Функция для генерации случайного имени файла
def generate_random_filename(extension):
    return ''.join(random.choices(string.ascii_uppercase + string.digits, k=24)) + extension


# Функция для рендеринга HTML в изображение
def html_to_image(html_content, image_file_path):
    hti = Html2Image(output_path=IMAGE_FOLDER)

    # Добавляем белый фон к HTML
    html_with_background = f"""
    <html>
    <head>
        <style>
            body {{
                background-color: white;
                margin-left: 50px;  /* Отступ слева */
                margin-right: 50px; /* Отступ справа */
                padding: 0;
            }}
        </style>
    </head>
    <body>
    {html_content}
    </body>
    </html>
    """

    hti.screenshot(html_str=html_with_background, save_as=image_file_path)


# Функция для вставки изображения в PDF
def image_to_pdf(image_file_path, pdf_file_path):
    c = canvas.Canvas(pdf_file_path, pagesize=letter)
    c.drawImage(image_file_path, 0, 0, width=letter[0], height=letter[1])
    c.showPage()
    c.save()


# Функция для генерации PDF с изображением
def generate_pdf(html_content):
    # Генерация случайных имен файлов
    image_filename = generate_random_filename('.png')
    pdf_filename = generate_random_filename('.pdf')

    image_file_path = os.path.join(IMAGE_FOLDER, image_filename)
    pdf_file_path = os.path.join(UPLOAD_FOLDER, pdf_filename)

    # Проверка и создание папки, если ее нет
    if not os.path.exists(UPLOAD_FOLDER):
        os.makedirs(UPLOAD_FOLDER)
    if not os.path.exists(IMAGE_FOLDER):
        os.makedirs(IMAGE_FOLDER)

    # Рендеринг HTML в изображение
    try:
        html_to_image(html_content, image_filename)
    except Exception as e:
        raise HTTPException(status_code=400, detail=f"Ошибка при рендеринге изображения: {str(e)}")

    # Генерация PDF с изображением
    try:
        image_to_pdf(image_file_path, pdf_file_path)
    except Exception as e:
        raise HTTPException(status_code=400, detail=f"Ошибка при создании PDF: {str(e)}")

    # Возвращаем путь к файлу PDF
    return f'/download/{pdf_filename}'
