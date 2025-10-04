@echo off
echo.
echo ========================================
echo   Medical Chatbot - Auto Setup
echo ========================================
echo.

echo [1/4] Copying environment configuration...
if not exist .env (
    copy .env.example .env
    echo ✅ Environment file created
) else (
    echo ⚠️  .env file already exists, skipping...
)

echo.
echo [2/4] Generating application key...
php artisan key:generate --force

echo.
echo [3/4] Clearing configuration cache...
php artisan config:clear

echo.
echo [4/4] Setting up database automatically...
php artisan setup:database

echo.
echo ========================================
echo   Setup Complete! 🎉
echo ========================================
echo.
echo Your Medical Chatbot is ready to use!
echo.
pause
