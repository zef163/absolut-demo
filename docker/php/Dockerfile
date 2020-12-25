FROM php:7.4-apache

# Install dependencies
RUN apt-get update && apt-get install -y zip unzip libzip-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql zip

# Install Opcache
RUN docker-php-ext-configure opcache --enable-opcache && docker-php-ext-install opcache
COPY ./docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Host
COPY ./docker/php/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Copy project
COPY . /var/www/html/

EXPOSE 80

CMD ["apache2-foreground"]
