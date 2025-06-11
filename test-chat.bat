@echo off
echo Starting Chat Test Environment...
echo.

echo 1. Starting Laravel Development Server...
start /B cmd /c "cd /d c:\laragon\www\Filament---Inventaris-Kantor && php artisan serve --port=8000"

timeout /t 3 /nobreak >nul

echo 2. Opening Admin Panel (login as admin)...
start "" "http://localhost:8000/admin/login"

echo 3. Opening User Frontend (login as user)...  
start "" "http://localhost:8000/login"

echo.
echo Test Instructions:
echo 1. Login ke admin panel dengan: admin@admin.com / password
echo 2. Login ke user frontend dengan: user@user.com / password  
echo 3. Di user frontend, navigate ke Chat dan kirim pesan
echo 4. Di admin panel, go to Chat Support dan balas pesan
echo 5. Check real-time updates di kedua browser
echo.
echo Laravel Reverb Server harus sudah running di port 8080
echo Press any key to close testing environment...
pause >nul

echo Stopping development server...
taskkill /f /im php.exe >nul 2>&1
