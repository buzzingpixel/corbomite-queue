@echo off

docker-compose -f docker-compose.yml -p corbomite-queue up -d
docker exec -it --user root --workdir /app php-corbomite-queue bash -c "cd /app && composer install"
