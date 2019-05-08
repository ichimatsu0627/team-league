FROM php:7.2-fpm

RUN usermod -u 1000 www-data \
    && groupmod -g 1000 www-data

RUN docker-php-ext-install mysqli
RUN pecl install APCu
RUN echo 'extension=apcu.so' > /usr/local/etc/php/conf.d/apcu.ini
# RUN echo 'zend_extension=xdebug.so' > /usr/local/etc/php/conf.d/xdebug.ini \
#     && echo 'xdebug.remote_enable=1' >> /usr/local/etc/php/conf.d/xdebug.ini \
#     && echo 'xdebug.remote_autostart=1' >> /usr/local/etc/php/conf.d/xdebug.ini \
#     && echo 'xdebug.remote_host=10.254.254.254' >> /usr/local/etc/php/conf.d/xdebug.ini \
#     && echo 'xdebug.remote_port=9001' >> /usr/local/etc/php/conf.d/xdebug.ini \
#     && echo 'xdebug.profiler_enable=1' >> /usr/local/etc/php/conf.d/xdebug.ini \
#     && echo 'xdebug.profiler_output_dir="/tmp"' >> /usr/local/etc/php/conf.d/xdebug.ini \
#     && echo 'xdebug.remote_log=/tmp/xdebug.log' >> /usr/local/etc/php/conf.d/xdebug.ini \
#     && echo 'xdebug.max_nesting_level=1000' >> /usr/local/etc/php/conf.d/xdebug.ini \
#     && echo 'xdebug.remote_connect_back=0' >> /usr/local/etc/php/conf.d/xdebug.ini \
#     && echo 'xdebug.extended_info=1' >> /usr/local/etc/php/conf.d/xdebug.ini \
#     && echo 'xdebug.idekey=xdebug' >> /usr/local/etc/php/conf.d/xdebug.ini 
RUN pecl install redis && docker-php-ext-enable redis

COPY php.ini /usr/local/etc/php/
# COPY zzz-www.conf /usr/local/etc/php-fpm.d/zzz-www.conf
