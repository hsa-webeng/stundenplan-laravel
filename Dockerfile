# syntax=docker/dockerfile:1

FROM node:20-alpine as node-dev

WORKDIR /var/www/html

# copy package.json and package-lock.json to the working directory
COPY package.json package-lock.json ./

# install packages from package.json
RUN npm install

# move the node_modules to the parent directory
RUN mv node_modules /var/www

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
    supervisor \
    netcat-openbsd

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy composer itself
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the rest of the app files
COPY . /var/www/html

# remove vendor & node_modules folder
RUN rm -rf /var/www/html/vendor
RUN rm -rf /var/www/html/node_modules

RUN --mount=type=bind,source=composer.json,target=composer.json \
    --mount=type=bind,source=composer.lock,target=composer.lock \
    --mount=type=cache,target=/tmp/cache \
    composer install --no-interaction

# move the vendor folder to parent directory
RUN mv vendor /var/www

# delete all contents of the html folder (will be mounted as volume)
RUN rm -rf /var/www/html/*
RUN mkdir -p /var/www/html/public

# set document root to public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN groupadd -g 1000 wwwgroup && useradd -u 1000 -g wwwgroup -m wwwuser;
