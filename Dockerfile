FROM bitnami/apache

RUN apt-get update
RUN curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash -
RUN apt-get install -y nodejs

WORKDIR /usr/src/app
COPY . ./

RUN npm install
RUN npm run build

RUN cp -r ./build /opt/bitnami/apache/htdocs

EXPOSE 80
