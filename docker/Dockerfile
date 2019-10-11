FROM node:lts-alpine

WORKDIR /app/

COPY backend/package.json .
COPY backend/package-lock.json .

RUN ["npm", "ci"]

COPY backend/. .

CMD ["npm", "run", "dev"]
