# syntax=docker/dockerfile:1

FROM php:8.3-apache AS final

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    supervisor

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy composer itself
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the rest of the app files
COPY . /var/www/html

# remove vendor folder if exists
RUN rm -rf /var/www/html/vendor

RUN --mount=type=bind,source=composer.json,target=composer.json \
    --mount=type=bind,source=composer.lock,target=composer.lock \
    --mount=type=cache,target=/tmp/cache \
    composer install --no-interaction

# copy the vendor folder to parent directory
RUN cp -r /var/www/html/vendor /var/www

# Set the user to run the container
RUN chown=www-data:www-data . /var/www

USER www-data
