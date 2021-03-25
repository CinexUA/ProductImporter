FROM php:7.4-fpm

ARG DEBIAN_FRONTEND=noninteractive

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -yq \
    openssl \
    build-essential \
    libonig-dev libpq-dev libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    mcrypt \
    mariadb-client \
    jpegoptim optipng pngquant gifsicle \
    nano \
    zip unzip \
    git \
    curl && \

    # Clear cache
    apt-get clean && rm -rf /var/lib/apt/lists/* && \

    # Install extensions
    #docker-php-ext-install bcmath ctype exif fileinfo json mbstring pcntl pdo pdo_mysql zip && \
    docker-php-ext-configure zip && \
    docker-php-ext-install bcmath exif pcntl pdo_mysql zip && \
    docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ && \

    docker-php-ext-install gd && \

    # Install composer
    cd /tmp && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \

    # Add user for laravel application
    groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/bash -g www www


# Change current root user to www
#USER www

