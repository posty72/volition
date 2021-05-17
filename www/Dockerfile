FROM php:5.6-apache

RUN docker-php-ext-install mysqli

RUN apt-get update -y
RUN apt-get install mysql-client -y

COPY . /var/www/html/

CMD ["apache2-foreground"]