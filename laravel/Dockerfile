FROM php:8.2-fpm

# Установка необходимых пакетов для PHP и Node.js
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    nodejs \
    npm \
    nginx

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Установка необходимых расширений PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo pdo_mysql

# Настройка прав доступа
RUN chown -R www-data:www-data /var/www/html

# Копирование файлов проекта
COPY . /var/www/html

# Установка рабочей директории
WORKDIR /var/www/html
