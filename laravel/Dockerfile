FROM php:8.2-apache

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

# Включение модуля mod_rewrite для Apache
RUN a2enmod rewrite

# Настройка Apache для обслуживания из директории public
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN echo "<Directory /var/www/html/public> \n\
    Options Indexes FollowSymLinks \n\
    AllowOverride All \n\
    DirectoryIndex index.php \n\
    Require all granted \n\
</Directory>" >> /etc/apache2/apache2.conf

# Копирование файлов проекта
COPY . /var/www/html


# Переход в директорию проекта
WORKDIR /var/www/html

