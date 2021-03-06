#!/bin/bash
SCRIPT_DIR="$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"

cd $SCRIPT_DIR;

docker-compose pull php
docker-compose up -d --force-recreate
docker-compose exec php 'rsync -a dist/ public/' &
