#!/bin/bash

# Ждем 10 секунд для инициализации зависимостей
sleep 10

# Настройка таймаута для Composer
composer config --global process-timeout 600

# Устанавливаем зависимости через Composer
composer install --no-interaction --prefer-dist --optimize-autoloader

# Устанавливаем зависимости NPM
npm install --fetch-timeout=300000 --fetch-retries=3

# Собираем фронтенд
npm run build

# Генерируем ключ приложения
php artisan key:generate

# Выполняем миграции и наполнение базы данных
php artisan migrate --seed

# Устанавливаем правильные права для storage и bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Запускаем PHP-FPM
exec php-fpm
