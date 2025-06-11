@echo off
echo Starting Laravel Reverb WebSocket Server...
echo.
echo Make sure to keep this window open while using chat features.
echo Press Ctrl+C to stop the server.
echo.
cd /d "c:\laragon\www\Filament---Inventaris-Kantor"
php artisan reverb:start --host=0.0.0.0 --port=8080
