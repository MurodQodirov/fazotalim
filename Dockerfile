FROM php:8.3-apache

RUN a2enmod rewrite \
 && sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

WORKDIR /var/www/html
COPY . /var/www/html

RUN mkdir -p /var/www/html/data /var/www/html/uploads/videos \
 && chown -R www-data:www-data /var/www/html \
 && chmod -R 775 /var/www/html/data /var/www/html/uploads

COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80
CMD ["/usr/local/bin/start.sh"]
