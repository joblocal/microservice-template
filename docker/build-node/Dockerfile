FROM node:10.8.0-alpine

LABEL maintainer="joblocal GmbH <produktentwicklung@joblocal.de>"

RUN apk add --update python3 \
  curl \
  bash \
  && rm -rf /var/cache/apk/*

RUN curl -O https://bootstrap.pypa.io/get-pip.py \
    && python3 get-pip.py \
    && pip install --upgrade pip \
    && pip install awscli --upgrade
