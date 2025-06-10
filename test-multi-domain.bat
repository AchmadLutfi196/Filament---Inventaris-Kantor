@echo off
echo Testing Multi-Domain Setup...

echo.
echo 1. Testing Docker containers...
docker-compose ps

echo.
echo 2. Testing User Domain (user.inventaris.local)...
curl -I -s "http://user.inventaris.local" | findstr "HTTP"
if errorlevel 1 (
    echo [ERROR] User domain not accessible
) else (
    echo [OK] User domain accessible
)

echo.
echo 3. Testing Admin Domain (admin.inventaris.local:8080)...
curl -I -s "http://admin.inventaris.local:8080" | findstr "HTTP"
if errorlevel 1 (
    echo [ERROR] Admin domain not accessible
) else (
    echo [OK] Admin domain accessible
)

echo.
echo 4. Testing Database Connection...
docker-compose exec -T app php artisan migrate:status | findstr "Migration name"
if errorlevel 1 (
    echo [ERROR] Database connection failed
) else (
    echo [OK] Database connection successful
)

echo.
echo 5. Testing Redis Connection...
docker-compose exec -T redis redis-cli ping | findstr "PONG"
if errorlevel 1 (
    echo [ERROR] Redis connection failed
) else (
    echo [OK] Redis connection successful
)

echo.
echo 6. Testing WebSocket (Reverb)...
netstat -an | findstr ":9090" | findstr "LISTENING"
if errorlevel 1 (
    echo [ERROR] Reverb WebSocket not running
) else (
    echo [OK] Reverb WebSocket running on port 9090
)

echo.
echo Testing completed!
echo.
echo If any errors found, check:
echo - Docker containers: docker-compose logs
echo - Hosts file: C:\Windows\System32\drivers\etc\hosts
echo - Firewall settings
echo.

pause
