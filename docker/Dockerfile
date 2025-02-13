FROM php:8.2.27-fpm

#ENV MIX_BASE_API=/api

COPY . /var/www/html

# Встановлюємо залежності для PHP, Nginx, Composer, Node.js, npm
RUN apt-get update -y && apt-get install -y \
    nginx wget zip npm unzip git curl \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libmagickwand-dev imagemagick \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Встановлюємо PHP-розширення
RUN docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-install mysqli \
    && docker-php-ext-configure mysqli --with-mysqli=mysqlnd \
    && docker-php-ext-install gd \
    && docker-php-ext-configure gd

# Встановлюємо Imagick
RUN pecl install imagick && docker-php-ext-enable imagick

# Налаштовуємо php.ini
RUN echo -e "max_execution_time = 1000\nmax_input_time = 1000" > /usr/local/etc/php/php.ini

# Встановлюємо Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --quit

# Копіюємо конфігурації та .env
COPY docker/default.conf /etc/nginx/sites-enabled/default
COPY docker/entrypoint.sh /usr/bin/entrypoint.sh
COPY .env.example /var/www/html/.env

# Надання прав доступу для entrypoint.sh
RUN chmod +x /usr/bin/entrypoint.sh

# Встановлюємо робочу директорію
WORKDIR /var/www/html

# Встановлюємо PHP залежності за допомогою Composer
RUN composer install --no-interaction --prefer-dist

# Встановлюємо Node.js залежності
RUN npm install --legacy-peer-deps

# Створюємо фронтенд збірку
RUN npm run build

# Видаляємо node_modules після зборки для зменшення розміру образу
RUN rm -rf /var/www/html/node_modules

# Оновлюємо права на файли
RUN chown -R www-data:www-data /var/www/html

# Стартуємо PHP та Laravel команди через entrypoint.sh
ENTRYPOINT ["/usr/bin/entrypoint.sh"]
