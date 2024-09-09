FROM python:3.12

# Установка distutils
RUN apt-get update && apt-get install -y python3-pip python3-setuptools

WORKDIR /app

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

COPY . /app

CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "5000"]
