FROM php:8.3.0-apache

ARG UID

# Create user and some useful stuff
RUN adduser -u ${UID} --disabled-password --gecos "" appuser
RUN mkdir /home/appuser/.ssh
RUN chown -R appuser:appuser /home/appuser/
RUN echo "StrictHostKeyChecking no" >> /home/appuser/.ssh/config
RUN echo "export COLUMNS=300" >> /home/appuser/.bashrc
RUN echo "alias sf=/var/www/canoe/bin/console" >> /home/appuser/.bashrc

COPY ./php.ini /usr/local/etc/php/php.ini

# Install packages and PHP extensions
RUN apt-get update \
    && apt-get upgrade -y \
    # common libraries and extensions
    && apt-get install -y vim ca-certificates apt-transport-https software-properties-common lsb-release \
    && apt-get install -y curl bzip2 zip unzip xz-utils gnupg git acl openssl openssh-client wget \
    && apt-get install -y libpng-dev zlib1g-dev libzip-dev libbz2-dev libgmp-dev libxml2-dev libtidy-dev libxslt1-dev libicu-dev \
    && docker-php-ext-install -j "$(nproc)" bcmath bz2 calendar gettext gmp intl soap tidy xsl zip pdo gd \
    # for MySQL
    && docker-php-ext-install -j "$(nproc)" pdo_mysql \
    # for Postgres
    && apt-get install -y libpq-dev \
    && docker-php-ext-install -j "$(nproc)" pdo_pgsql \
    # for RabbitMQ and XDEBUG
    && docker-php-ext-install -j "$(nproc)" sockets \
    && apt-get install -y librabbitmq-dev libssh-dev \
    && pecl install apcu-5.1.23 amqp-2.1.1 xdebug-3.3.0 \
    # enable Docker extensions
    && docker-php-ext-enable --ini-name 05-opcache.ini opcache apcu amqp xdebug bcmath bz2 calendar gettext gmp intl soap tidy xsl zip pdo_mysql pdo_pgsql gd \
    && echo "Done."

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install NODE 20 and Yarn
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get -y install nodejs
RUN curl -sL https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list
RUN apt-get update && apt-get install yarn

# Install PHP-CS-FIXER
RUN wget https://cs.symfony.com/download/php-cs-fixer-v3.phar -O php-cs-fixer
RUN chmod a+x php-cs-fixer
RUN mv php-cs-fixer /usr/local/bin/php-cs-fixer

## Install Symfony binary
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt-get update && apt-get install -y symfony-cli

RUN a2enmod rewrite

COPY ./canoe.conf /etc/apache2/sites-available/canoe.conf

RUN a2ensite canoe
RUN a2dissite 000-default

ENV APACHE_RUN_USER appuser
ENV APACHE_RUN_GROUP appuser

USER appuser
WORKDIR /var/www/canoe/
