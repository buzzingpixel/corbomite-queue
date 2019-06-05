@echo off

set cmd=%1
set allArgs=%*
for /f "tokens=1,* delims= " %%a in ("%*") do set allArgsExceptFirst=%%b
set secondArg=%2
set valid=false

:: If no command provided, list commands
if "%cmd%" == "" (
    set valid=true
    echo The following commands are available:
    echo   .\dev up
    echo   .\dev down
    echo   .\dev phpunit [args]
    echo   .\dev cli [args]
    echo   .\dev devrun [args]
    echo   .\dev composer [args]
    echo   .\dev login [args]
)

if "%1%" == "up" (
    set valid=true
    call :up
)

if "%1%" == "down" (
    set valid=true
    docker-compose -f docker-compose.yml -p corbomite-queue down
)

if "%1%" == "phpunit" (
    set valid=true
    docker exec -it --user root --workdir /app php-corbomite-queue bash -c "chmod +x /app/vendor/bin/phpunit && /app/vendor/bin/phpunit --configuration /app/phpunit.xml %allArgsExceptFirst%"
)

if "%1%" == "cli" (
    set valid=true
    docker exec -it --user root --workdir /app php-corbomite-queue bash -c "php app %allArgsExceptFirst%"
)

if "%1%" == "devrun" (
    set valid=true
    docker exec -it --user root --workdir /app php-corbomite-queue bash -c "php devrun %allArgsExceptFirst%"
)

if "%1%" == "composer" (
    set valid=true
    docker exec -it --user root --workdir /app php-corbomite-queue bash -c "%allArgs%"
)

if "%1%" == "login" (
    set valid=true
    docker exec -it --user root %secondArg%-corbomite-queue bash
)

if not "%valid%" == "true" (
    echo Specified command not found
    exit /b 1
)

:: Exit with no error
exit /b 0

:up
    docker-compose -f docker-compose.yml -p corbomite-queue up -d
    docker exec -it --user root --workdir /app php-corbomite-queue bash -c "cd /app && composer install"
:: Exit with no error
exit /b 0
