FROM joblocal/base-app:alpine-3.7

LABEL maintainer="joblocal GmbH <produktentwicklung@joblocal.de>"

RUN apk add --update \
  curl \
  ghostscript \
  imagemagick \
  php7-apcu \
  php7-fileinfo \
  php7-iconv \
  php7-imagick \
  php7-opcache \
  php7-phar \
  php7-simplexml \
  php7-tokenizer \
  php7-xsl \
  && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
  && rm -rf /var/cache/apk/*

WORKDIR /workspace

VOLUME /workspace
