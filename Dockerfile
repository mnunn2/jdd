FROM php:5.6-apache
COPY php.ini /usr/local/etc/php/
RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev
RUN docker-php-ext-install mysql mysqli mbstring zip 
#COMPOSER 
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

#PHPUNIT
RUN composer global require "phpunit/phpunit"
ENV PATH /root/.composer/vendor/bin:$PATH
RUN ln -s /root/.composer/vendor/bin/phpunit /usr/bin/phpunit

RUN pecl install xdebug && \
    echo 'zend_extension="/usr/local/lib/php/extensions/no-debug-non-zts-20131226/xdebug.so"' > /usr/local/etc/php/conf.d/xdebug.ini    

RUN echo 'xdebug.remote_enable=1' >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo 'xdebug.remote_host=172.17.0.1' >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo 'xdebug.remote_autostart=true' >> /usr/local/etc/php/conf.d/xdebug.ini

RUN echo 'date.timezone = Europe/London' > /usr/local/etc/php/conf.d/date.ini

Run a2enmod rewrite
Run service apache2 restart


