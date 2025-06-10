@echo off
echo Starting Inventaris Multi-Domain Development Environment...

:: Check if Docker is running
docker --version >nul 2>&1
if errorlevel 1 (
    echo Error: Docker is not running or not installed!
    echo Please start Docker Desktop and try again.
    pause
    exit /b 1
)

:: Start Docker containers
echo Starting Docker containers...
docker-compose up -d

:: Wait for services to be ready
echo Waiting for services to initialize...
timeout /t 10

:: Show container status
echo.
echo Container Status:
docker-compose ps

echo.
echo Services are starting up...
echo.
echo User Frontend: http://user.inventaris.local
echo Admin Panel: http://admin.inventaris.local:8080
echo.
echo To stop services: docker-compose down
echo To view logs: docker-compose logs -f
echo.

pause
