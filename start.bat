@echo off
chcp 65001 > nul
title Новостной сайт на Laravel

echo =========================================================
echo    НОВОСТНОЙ САЙТ НА LARAVEL (СТУДЕНЧЕСКИЙ ПРОЕКТ)
echo =========================================================
echo.

if not exist .env (
    echo [1/4] Создание конфигурационного файла .env...
    copy .env.example .env > nul
) else (
    echo [1/4] Конфигурация .env найдена.
)

if not exist database\database.sqlite (
    echo [2/4] Создание файла базы данных SQLite...
    type nul > database\database.sqlite
) else (
    echo [2/4] База данных SQLite найдена.
)

echo [3/4] Проверка таблиц и пользователей...
php artisan migrate --force > nul 2>&1
php artisan db:seed --force > nul 2>&1

echo [4/4] Запуск локального веб-сервера...
echo.
echo =========================================================
echo  Сайт доступен в браузере: http://127.0.0.1:8000
echo.
echo  Тестовые аккаунты для сдачи (пароль 12345):
echo   - admin@test.ru       (Администратор)
echo   - journalist@test.ru  (Журналист)
echo   - user@test.ru        (Читатель)
echo.
echo  На странице входа (/login) есть кнопки быстрого входа в 1 клик!
echo =========================================================
echo.
echo Для остановки сервера нажмите Ctrl + C
echo.

php artisan serve --port=8000
pause
