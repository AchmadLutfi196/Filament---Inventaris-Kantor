@echo off
echo Setting up Multi-Domain Inventaris System with Docker...

:: Create SSL directory
if not exist "docker\nginx\ssl" mkdir docker\nginx\ssl

:: Copy environment file
if not exist ".env" (
    copy .env.docker .env
    echo Environment file created from .env.docker
)

:: Generate application key if not exists
php artisan key:generate --ansi

:: Add domains to hosts file (requires admin privileges)
echo.
echo Adding domains to hosts file...
echo Please run PowerShell as Administrator and execute:
echo Add-Content -Path C:\Windows\System32\drivers\etc\hosts -Value "127.0.0.1 user.inventaris.local"
echo Add-Content -Path C:\Windows\System32\drivers\etc\hosts -Value "127.0.0.1 admin.inventaris.local"
echo.

:: Build and start Docker containers
echo Building Docker containers...
docker-compose down
docker-compose build --no-cache
docker-compose up -d

:: Wait for MySQL to be ready
echo Waiting for MySQL to be ready...
timeout /t 30

:: Run Laravel setup commands
echo Running Laravel setup...
docker-compose exec app composer install
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan db:seed --force
docker-compose exec app php artisan storage:link
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

:: Install and build frontend assets
echo Installing and building frontend assets...
docker-compose exec app npm install
docker-compose exec app npm run build

echo.
echo Setup complete!
echo.
echo User Domain: http://user.inventaris.local
echo Admin Domain: http://admin.inventaris.local:8080
echo.
echo Please add the following to your hosts file:
echo 127.0.0.1 user.inventaris.local
echo 127.0.0.1 admin.inventaris.local
