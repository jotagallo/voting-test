FROM php:8.2-fpm

USER root

RUN apt-get update
RUN apt-get install -y --fix-missing  \
	git \
	wget \ 
	vim \
	zip \
	unzip \
	npm \
	libz-dev \
	libpng-dev \
	libjpeg-dev \
	libfreetype6-dev \
	libxslt-dev \
	libssl-dev \
	default-mysql-client

RUN docker-php-ext-install \
	gd \
	opcache \
	mysqli \
	xsl \
	calendar \
	intl \
	exif \
	ftp \
	bcmath \
	pdo \
	soap \
	pdo_mysql
COPY ./php-fpm/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# GD JPEG and Freetype support
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Drush
RUN wget -O drush.phar https://github.com/drush-ops/drush-launcher/releases/download/0.10.2/drush.phar && \
	chmod +x drush.phar && \
	mv drush.phar /usr/local/bin/drush

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN composer config -g repo.packagist composer https://packagist.org

WORKDIR /var/www/html/

RUN mkdir -p /var/www/html/vendor
RUN mkdir -p /var/www/html/web/sites/default/files
RUN chmod 777 -R /var/www/html/web/sites/default/files
