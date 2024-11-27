FROM php:8.3-cli AS php-backend

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    zlib1g-dev \
    curl \
    librabbitmq-dev \
    && docker-php-ext-install \
    pdo_mysql \
    zip \
    bcmath \
    sockets \
    pcntl \
    && pecl install amqp \
    && docker-php-ext-enable amqp

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY composer.json /var/www/html/
RUN if [ -f composer.lock ]; then cp composer.lock /var/www/html/; fi

RUN composer install --no-dev --optimize-autoloader

COPY ./src /var/www/html/

FROM node:20 AS react-frontend

WORKDIR /app

COPY ./react-app /app

RUN npm install
RUN npm run build

FROM php:8.3-cli AS final

COPY --from=php-backend / /

COPY --from=react-frontend /app/dist /var/www/html/public/dashboard

WORKDIR /var/www/html

EXPOSE 9999

CMD ["php", "-S", "0.0.0.0:9999", "-t", "/var/www/html/public"]
