FROM alpine:3.7

LABEL maintainer="joblocal GmbH <produktentwicklung@joblocal.de>"

ENV \
    DEPS="ca-certificates \
        memcached \
        php7 \
        php7-curl \
        php7-ctype \
        php7-dom \
        php7-memcached \
        php7-mbstring \
        php7-json \
        php7-xml \
        php7-pdo_mysql \
        php7-pdo_sqlite \
        php7-simplexml \
        runit \
        bash"

RUN addgroup -g 1000 -S www-data \
  && adduser -u 1000 -D -S -G www-data www-data

RUN apk add --update $DEPS \
  && rm -rf /var/cache/apk/*

# Set Timezone to UTC
RUN apk add --update tzdata \
  && cp /usr/share/zoneinfo/UTC /etc/localtime \
  && echo "UTC" >  /etc/timezone \
  && apk del tzdata \
  && rm -rf /var/cache/apk/*

COPY ./rootfs /

CMD ["/usr/bin/queuelistener.sh"]
