FROM php:7-apache

RUN apt-get update && apt-get install -y \
    # development packages
    zip \
    nano \
    less \
    curl \
    sudo \
    unzip \
    sendmail \
    libicu-dev \
    libpng-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    # php extensions
    && docker-php-ext-install \
       exif \
       bcmath \
       mysqli \
       opcache \
       calendar \
       pdo_mysql \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    # apache config
    # mod_rewrite for URL rewrite and mod_headers for .htaccess extra headers like Access-Control-Allow-Origin-
    && a2enmod rewrite headers \
    # we need a user with the same UID/GID with host user
    # so when we execute CLI commands, all the host file's ownership remains intact
    # otherwise command from inside container will create root-owned files and directories
    && useradd -G www-data,root -u 1000 -d /home/devuser devuser \
    && mkdir /home/devuser \
    && chown -R devuser:devuser /home/devuser \
    # clean and autoremove the image
    && apt-get autoremove -y \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/* \
    # local configs in container
    # remove config
    && rm -rf /etc/apache2/apache2.conf \
    # sendmail config
    && sed -i -e "/^VERSIONID/i include(\`/etc/mail/tls/starttls.m4')dnl" -e "s/, Addr=127.0.0.1//g" /etc/mail/sendmail.mc \
    && sendmailconfig

# copy config
# php
COPY ./php/conf/php.ini /usr/local/etc/php
# apache
COPY ./apache/conf/apache2.conf /etc/apache2/apache2.conf

# This hook adds to the end of the file a line with hostname and ip of the running container for
# sendmail to work correctly and starts apache. If the file is not edited in CMD, changes to
# the container will not be saved.
CMD echo "$(hostname -i) $(hostname) $(hostname).localdomain" >> /etc/hosts \
    && service sendmail restart \
    && apache2-foreground
