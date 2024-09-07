# Используем PHP 8.1 с Apache
FROM php:8.2-apache

# Устанавливаем необходимые расширения PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Устанавливаем зависимости Laravel
WORKDIR /var/www/html
COPY . .
RUN composer install

# Настройки прав доступа для хранения и кеша
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Копируем конфигурацию Apache
COPY ./docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Включаем mod_rewrite для Laravel
RUN a2enmod rewrite

# Открываем 80 порт
EXPOSE 80

# Запуск Apache
CMD ["apache2-foreground"]
