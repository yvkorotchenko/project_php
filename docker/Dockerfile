FROM php:8.0.5-fpm

ARG CI_PROJECT_NAME

ENV MIX_BASE_API=/api

COPY $CI_PROJECT_NAME /var/www/html

RUN apt-get update -y && apt-get install -y nginx && apt-get install -y libjpeg-dev libpng-dev libfreetype6-dev && apt-get install -y wget && apt-get install -y zip unzip npm git

RUN apt update; \
    apt install -y curl wget libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev \
    libjpeg-dev libpng-dev libmagickwand-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-install mysqli \
    && docker-php-ext-configure mysqli --with-mysqli=mysqlnd \
    && docker-php-ext-install gd \
    && docker-php-ext-configure gd

RUN pecl install imagick  && docker-php-ext-enable imagick

RUN echo -e "max_execution_time = 1000\nmax_input_time = 1000" > /usr/local/etc/php/php.ini

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --quit

COPY docker/default.conf /etc/nginx/sites-enabled/default

COPY docker/entrypoint.sh /usr/bin/entrypoint.sh

RUN chmod +x /usr/bin/entrypoint.sh

WORKDIR /var/www/html

RUN composer install

RUN npm i

RUN npm run production

RUN rm -rf /var/www/html/node_modules

RUN chown -R www-data:www-data /var/www/html

ENTRYPOINT ["/usr/bin/entrypoint.sh"]
