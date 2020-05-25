FROM php:7.4-fpm

RUN apt-get update 
# Install Git 
RUN apt-get -y install git
RUN apt-get install -y libmcrypt-dev \
     libmagickwand-dev --no-install-recommends \
    
    && docker-php-ext-install  pdo_mysql
# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


