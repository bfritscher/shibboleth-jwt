FROM php:5-apache
RUN set -x; \
    apt-get update \
    && apt-get install -y --no-install-recommends \
    curl \
    git \
    zip \
    unzip \
    bzip2 \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /var/cache/apt/archives/*
RUN curl -sS https://getcomposer.org/installer | php
RUN php composer.phar require lcobucci/jwt
