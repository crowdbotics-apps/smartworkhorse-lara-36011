FROM php:7
RUN apt-get update -y && apt-get install -y openssl zip unzip git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
WORKDIR /app
COPY . /app
RUN chmod -R 755 /app/storage
RUN composer install
CMD php artisan serve --host=0.0.0.0 --port=$PORT
