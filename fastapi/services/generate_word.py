# Путь, где будут храниться документы
import os
import random
import string
from docx import Document
from htmldocx import HtmlToDocx

UPLOAD_FOLDER = 'export_docs/'


def generate_doc(html_content):
    # Генерация случайного имени файла
    res = ''.join(random.choices(string.ascii_uppercase + string.digits, k=24))
    file_name = res + '.docx'

    # Создаем новый документ Word
    doc = Document()
    new_parser = HtmlToDocx()
    new_parser.table_style = 'Light Shading Accent 4'
    # Добавляем блок <head> для поддержки кириллицы и шрифтов
    full_html = f"""
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            @font-face {{
                font-family: 'DejaVuSans';
                src: url('https://cdnjs.cloudflare.com/ajax/libs/font-dejavu/2.37/ttf/DejaVuSans.ttf');
            }}
            body {{
                font-family: 'DejaVuSans', sans-serif;
            }}
        </style>
    </head>
    <body>
    {html_content}
    </body>
    </html>
    """
    # Пример HTML-контента
    new_parser.add_html_to_document(full_html, doc)

    # Сохраняем документ
    file_path = os.path.join(UPLOAD_FOLDER, file_name)
    doc.save(file_path)

    # Возвращаем ссылку на скачивание
    download_link = f'/download/{file_name}'
    return download_link
