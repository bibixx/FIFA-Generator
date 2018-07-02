FROM php:7.2.0-apache

RUN apt-get update && apt-get install -my wget gnupg
RUN curl -sL https://deb.nodesource.com/setup_8.x | bash -
RUN apt-get install -y nodejs

COPY package.json /tmp/package.json
COPY package-lock.json /tmp/package-lock.json
RUN cd /tmp && npm install

WORKDIR /usr/src/app
RUN mkdir node_modules
RUN cp -a /tmp/node_modules ./

COPY . ./

RUN npm run build

RUN rm -rf /var/www/html/*
RUN cp -r ./build/* /var/www/html/
RUN ls /var/www/html/

EXPOSE 80