#FROM harbor.dev.kubix.tm.com.my/trust/trustwebbase:1 
FROM php:7.2-apache

#RUN apt-get update && apt-get install -y git

# 1. development packages
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libldap2-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    g++

# 2. apache configs + document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf


# 3. mod_rewrite for URL rewrite and mod_headers for .htaccess extra headers like Access-Control-Allow-Origin-
RUN a2enmod rewrite headers

# 4. start with base php config, then add extensions
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN sed -i 's/128M/512M/g' /usr/local/etc/php/php.ini
COPY uploadmax.ini $PHP_INI_DIR/conf.d/

RUN docker-php-ext-install \
    bz2 \
    intl \
    iconv \
    bcmath \
    opcache \
    calendar \
    mbstring \
    pdo_mysql \
    zip

RUN docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu
RUN docker-php-ext-install ldap


COPY . /var/www/html
COPY .env.example /var/www/html/\.env
RUN chown -R www-data:www-data /var/www/html/storage

RUN cd /var/www/html &&  php artisan key:generate
RUN cd /var/www/html &&  php artisan passport:keys
#CMD ["./run.sh"]
