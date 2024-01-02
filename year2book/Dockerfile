FROM php:8.1-apache

# Install nano and enable rewrite module
RUN apt-get update && \
    apt-get install -y nano && \
    a2enmod rewrite

COPY . /var/www/html/
RUN chmod -R 755 /var/www/html/storage/
RUN chown -R www-data:www-data /var/www/html/storage/

RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli

EXPOSE 8080
EXPOSE 80