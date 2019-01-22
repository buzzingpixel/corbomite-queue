@echo off

docker-compose up -d
docker exec -it --user root --workdir /app php-corbomite-queue bash -c "php %*"
