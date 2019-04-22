@echo off

docker exec -it --user root --workdir /app php-corbomite-queue bash -c "php %*"
