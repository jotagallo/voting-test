#!/bin/bash
docker build docker/
echo -e "\033[1mContainer setup completed !\033[0m"
docker compose up -d
CONTAINER_NAME="voting_drupal"
CID=$(docker ps -q -f status=running -f name=^/${CONTAINER_NAME}$)
if [ ! "${CID}" ]; then
  echo "Container ${CONTAINER_NAME} is not running. Make sure you started it running 'docker-compose up'"
else
  echo -e "\033[1mRunning Composer...\033[0m"
  docker exec -it ${CONTAINER_NAME} composer install
  echo -e "\033[1mDependencies installation finished !\033[0m"
  echo -e "\033[0;32mSite is ready to go! Visit it on http://localhost/\033[0m"
fi