# start from bitnami apache
FROM bitnami/apache

# Install Node.js, npm and ruby
RUN apt-get update
RUN apt-get install -y nodejs && ln -s `which nodejs` /usr/bin/node
RUN sudo apt-get install yarn

RUN yarn
RUN yarn build

# COPY config/php.ini /usr/local/etc/php/
COPY ./src /var/www/html/

EXPOSE 80
