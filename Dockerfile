FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    build-essential \
    libpq-dev \
    curl \
    git \
    unzip \
    && docker-php-ext-install pdo_pgsql pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY backendApi/ /var/www/html/

RUN chown -R www-data:www-data /var/www/html

RUN php -d memory_limit=-1 /usr/local/bin/composer install --optimize-autoloader --dev


RUN if [ -f /var/www/html/.env ]; then php /var/www/html/artisan key:generate; fi


EXPOSE 8000


CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www/html/public"]