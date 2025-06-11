@echo off
echo Stopping Inventaris Multi-Domain Development Environment...

:: Stop Docker containers
docker-compose down

echo.
echo All containers stopped successfully!
echo.

pause
