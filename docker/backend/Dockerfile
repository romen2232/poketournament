FROM php:8.3-fpm

# System dependencies
RUN apt update && apt install -yq \
    git vim zip unzip wget jq \
    libsodium-dev libicu-dev libpng-dev libjpeg62-turbo-dev libfreetype6-dev libwebp-dev libzip-dev zlib1g-dev pkg-config \
    && docker-php-ext-configure gd --with-jpeg --with-freetype --with-webp \
    && docker-php-ext-install -j$(nproc) intl exif bcmath zip gd \
    && pecl install xdebug \
    && printf "\n" | pecl install apcu \
    && printf "\n" | pecl install mongodb \
    && docker-php-ext-enable xdebug apcu mongodb \
    && apt clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

COPY php.ini /usr/local/etc/php/conf.d/


WORKDIR /var/www/backend

# Map to your host user
RUN usermod -u 1000 www-data && \
    groupmod -g 1000 www-data && \
    chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php-fpm", "-F"]
