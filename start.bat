@echo off
title Laravel News App

if not exist .env (
    copy .env.example .env > nul
    php artisan key:generate --force > nul
)
if not exist database\database.sqlite type nul > database\database.sqlite

php artisan migrate --force
php artisan db:seed --force

echo.
echo =========================================================
echo  Server starting at: http://127.0.0.1:8000
echo.
echo  Demo accounts [password: 12345]:
echo   - admin@test.ru       [Administrator]
echo   - journalist@test.ru  [Journalist]
echo   - user@test.ru        [Reader]
echo.
echo  Quick-login buttons available on /login page!
echo  Press Ctrl+C to stop the server
echo =========================================================
echo.

php artisan serve --port=8000
pause
