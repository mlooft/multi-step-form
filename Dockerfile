FROM wordpress:${WP_VERSION:-4.9.8}-php${PHP_VERSION:-7.2}-apache

ENV XDEBUG_PORT 9000 

RUN yes | pecl install xdebug && \
    echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini && \
	echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini && \
	echo "xdebug.idekey=vscode" >> /usr/local/etc/php/conf.d/xdebug.ini && \
	echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini && \
	echo "66.155.40.202 api.wordpress.org" >> /etc/hosts

EXPOSE 9000
