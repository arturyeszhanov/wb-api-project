# Используем официальный PHP-образ с необходимыми расширениями
FROM php:8.2-fpm

# Устанавливаем system зависимости
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Создание рабочей директории
WORKDIR /var/www

# Копируем проект в контейнер
COPY . .

# Установка зависимостей Laravel
RUN composer install --no-dev --optimize-autoloader

# Очистка кэша конфигураций
RUN php artisan config:clear
RUN php artisan route:clear

# Открываем порт, который Railway задаёт через переменную $PORT
ENV PORT=8000
EXPOSE ${PORT}

# Команда запуска
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT}"]
