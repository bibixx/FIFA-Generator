#!/bin/bash

# DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

#(
#  cd "$DIR/.." # Go to project dir.
#  ssh $SSH_USERNAME@$SSH_HOSTNAME -o StrictHostKeyChecking=no <<-EOF
    # cd $SSH_PROJECT_FOLDER
    # git pull
    # docker stack rm $CIRCLE_PROJECT_REPONAME
    # sleep 10
    # docker network ls
    docker build -t $CIRCLE_PROJECT_REPONAME .
    docker stack deploy --compose-file docker-compose.yml $CIRCLE_PROJECT_REPONAME
# EOF
# )