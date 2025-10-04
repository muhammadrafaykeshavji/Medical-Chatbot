# 🚀 Medical Chatbot - Quick Setup

## Automatic Database Creation

Your Medical Chatbot now supports **automatic MySQL database creation**! No need to manually create databases in phpMyAdmin.

## 🎯 Super Easy Setup

### Method 1: One-Click Setup (Windows)
```bash
setup.bat
```

### Method 2: Artisan Command
```bash
php artisan setup:database
```

## 📋 What It Does Automatically

✅ **Creates MySQL database** (`medical_ai`)  
✅ **Sets up all tables** (users, chat_conversations, health_metrics, etc.)  
✅ **Configures Laravel connection**  
✅ **Runs all migrations**  
✅ **Tests database connection**  
✅ **Shows database info**  

## 🔧 Prerequisites

1. **WAMP/XAMPP running** with MySQL service started
2. **Default MySQL settings**:
   - Host: `127.0.0.1`
   - Port: `3306`
   - Username: `root`
   - Password: (empty)

## 📊 After Setup

You can access your database through:
- **phpMyAdmin**: `http://localhost/phpmyadmin`
- **Database name**: `medical_ai`
- **Laravel Tinker**: `php artisan tinker`

## 🛠️ Advanced Options

```bash
# Force recreate database (if exists)
php artisan setup:database --force

# Check database status
php artisan db:show

# View all tables
php artisan tinker
DB::select('SHOW TABLES');
```

## 🎉 That's It!

Your Medical Chatbot database is now ready to use with full MySQL support and phpMyAdmin access!
