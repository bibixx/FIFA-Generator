# start from bitnami apache
FROM bitnami/apache

# Install Node.js, npm and ruby
RUN apt-get update
RUN apt-get install -y nodejs && ln -s `which nodejs` /usr/bin/node

curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | sudo apt-key add -
echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list
RUN sudo apt-get install yarn

RUN yarn
RUN yarn build

# COPY config/php.ini /usr/local/etc/php/
COPY ./src /var/www/html/

EXPOSE 80
