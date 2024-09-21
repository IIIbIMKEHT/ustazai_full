# Используем базовый образ PHP-FPM 8.2
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
    npm

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Установка необходимых расширений PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo pdo_mysql

# Копируем проект в контейнер
COPY . /var/www/html

# Устанавливаем права для директории
RUN chown -R www-data:www-data /var/www/html

# Устанавливаем рабочую директорию
WORKDIR /var/www/html

# Копируем скрипт entrypoint
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Устанавливаем entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]

# Экспонируем порт для PHP-FPM
EXPOSE 9000
