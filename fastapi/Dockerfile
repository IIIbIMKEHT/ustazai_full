# Используем Python 3.12
FROM python:3.12

# Устанавливаем рабочую директорию
WORKDIR /code

# Копируем файл с зависимостями
COPY ./requirements.txt /code/requirements.txt

# Устанавливаем зависимости
RUN pip install --no-cache-dir --upgrade -r /code/requirements.txt

# Копируем приложение
COPY ./app /code/app

# Открываем порт 9000
EXPOSE 9000

# Запускаем FastAPI с использованием uvicorn
CMD ["uvicorn", "app.main:app", "--host", "0.0.0.0", "--port", "9000"]