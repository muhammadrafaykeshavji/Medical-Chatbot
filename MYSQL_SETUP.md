# 🗄️ MySQL Database Setup Guide

## Prerequisites
- WAMP Server installed and running
- Apache and MySQL services started in WAMP

## 🚀 Automatic Setup (Recommended)

### Option 1: One-Click Setup
Just run the setup script:
```bash
setup.bat
```

### Option 2: Manual Command
```bash
php artisan setup:database
```

This will automatically:
- ✅ Create the MySQL database
- ✅ Set up all tables
- ✅ Configure everything for you

## 📋 Manual Setup (Alternative)

### 1. Update Environment Configuration
1. Copy `.env.example` to `.env`:
   ```bash
   copy .env.example .env
   ```

2. Your `.env` should have these database settings:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=medical_ai
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### 3. Generate Application Key
```bash
php artisan key:generate
```

### 4. Clear Configuration Cache
```bash
php artisan config:clear
php artisan cache:clear
```

### 5. Run Database Migrations
```bash
php artisan migrate:fresh
```

### 6. Verify Database Connection
```bash
php artisan db:show
```

## Accessing Your Database

### Through phpMyAdmin
- URL: `http://localhost/phpmyadmin`
- Select database: `medical_chatbot`
- View all tables and data

### Through Laravel Tinker
```bash
php artisan tinker
```
Then run:
```php
// View all users
User::all();

// View chat conversations
App\Models\ChatConversation::all();
```

## Tables Created
After migration, you'll have these tables:
- `users` - User accounts
- `chat_conversations` - AI chat sessions
- `chat_messages` - Individual messages
- `health_metrics` - Health tracking data
- `symptom_checks` - Symptom analysis results
- `medications` - Medication tracking
- System tables (cache, sessions, jobs, etc.)

## Troubleshooting

### Connection Issues
- Ensure WAMP MySQL service is running (green icon)
- Check if port 3306 is available
- Verify database name matches exactly: `medical_chatbot`

### Migration Errors
- Make sure database exists in phpMyAdmin
- Check MySQL user permissions
- Clear config cache: `php artisan config:clear`

## Success! 🎉
Once setup is complete, you can:
- ✅ Access database through phpMyAdmin
- ✅ Run your Laravel app with MySQL
- ✅ View and edit data visually
- ✅ Use all WAMP database tools
