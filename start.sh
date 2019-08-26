#!/bin/bash
docker-compose -f docker/docker-compose-dev.yml up --build -d
docker attach docker_backend_1

function finish {
  docker-compose -f docker/docker-compose-dev.yml down
}
trap finish EXIT
