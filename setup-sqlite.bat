@echo off
echo.
echo ========================================
echo   Medical Chatbot - SQLite Setup
echo ========================================
echo.

echo [1/5] Copying environment configuration...
if not exist .env (
    copy .env.example .env
    echo ✅ Environment file created
) else (
    echo ⚠️  .env file already exists, updating...
)

echo.
echo [2/5] Updating .env for SQLite...
powershell -Command "(Get-Content .env) -replace 'DB_CONNECTION=mysql', 'DB_CONNECTION=sqlite' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'DB_HOST=.*', '' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'DB_PORT=.*', '' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'DB_DATABASE=medical_ai', 'DB_DATABASE=database/database.sqlite' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'DB_USERNAME=.*', '' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'DB_PASSWORD=.*', '' | Set-Content .env"
echo ✅ SQLite configuration updated

echo.
echo [3/5] Creating SQLite database file...
if not exist database mkdir database
echo. > database\database.sqlite
echo ✅ SQLite database file created

echo.
echo [4/5] Generating application key...
php artisan key:generate --force

echo.
echo [5/5] Running database migrations...
php artisan migrate:fresh --force

echo.
echo ========================================
echo   SQLite Setup Complete! 🎉
echo ========================================
echo.
echo ✅ No MySQL required!
echo ✅ Database: database/database.sqlite
echo ✅ Ready to use: php artisan serve
echo.
pause
