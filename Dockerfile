FROM bitnami/apache

RUN apt-get update
RUN curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash -
RUN apt-get install -y nodejs

COPY package.json /tmp/package.json
RUN cd /tmp && npm install

WORKDIR /usr/src/app
RUN mkdir node_modules
RUN cp -a /tmp/node_modules ./

COPY . ./

RUN npm run build

RUN cp -r ./build /opt/bitnami/apache/htdocs

EXPOSE 80
