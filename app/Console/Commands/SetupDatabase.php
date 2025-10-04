<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use PDO;
use Exception;

class SetupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:database {--force : Force database creation even if it exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically create MySQL database and run migrations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting Medical Chatbot Database Setup...');
        
        // Step 0: Ensure APP_KEY is set
        if (empty(config('app.key'))) {
            $this->info('🔑 Generating application key...');
            $this->call('key:generate', ['--force' => true]);
            $this->info('✅ Application key generated!');
        }
        
        // Get database configuration
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        
        $this->info("📋 Database Configuration:");
        $this->line("   Host: {$host}");
        $this->line("   Port: {$port}");
        $this->line("   Database: {$database}");
        $this->line("   Username: {$username}");
        
        try {
            // Step 1: Connect to MySQL server (without database)
            $this->info('🔌 Connecting to MySQL server...');
            $pdo = new PDO("mysql:host={$host};port={$port}", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->info('✅ Connected to MySQL server successfully!');
            
            // Step 2: Check if database exists
            $stmt = $pdo->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?");
            $stmt->execute([$database]);
            $exists = $stmt->fetch();
            
            if ($exists && !$this->option('force')) {
                $this->warn("⚠️  Database '{$database}' already exists!");
                if (!$this->confirm('Do you want to continue anyway?')) {
                    $this->error('❌ Setup cancelled.');
                    return 1;
                }
            }
            
            // Step 3: Create database
            if (!$exists || $this->option('force')) {
                $this->info("🗄️  Creating database '{$database}'...");
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $this->info('✅ Database created successfully!');
            } else {
                $this->info('📁 Using existing database.');
            }
            
            // Step 4: Test Laravel database connection
            $this->info('🔗 Testing Laravel database connection...');
            DB::connection()->getPdo();
            $this->info('✅ Laravel database connection successful!');
            
            // Step 5: Run migrations
            $this->info('📊 Running database migrations...');
            $this->call('migrate:fresh', ['--force' => true]);
            
            // Step 6: Show database info
            $this->info('📈 Database setup completed! Here\'s your database info:');
            $this->call('db:show');
            
            // Step 7: Success message
            $this->newLine();
            $this->info('🎉 SUCCESS! Your Medical Chatbot database is ready!');
            $this->newLine();
            $this->line('📋 What you can do now:');
            $this->line('   • Access phpMyAdmin: http://localhost/phpmyadmin');
            $this->line('   • View database: ' . $database);
            $this->line('   • Start your app: php artisan serve');
            $this->newLine();
            
            return 0;
            
        } catch (Exception $e) {
            $this->error('❌ Database setup failed!');
            $this->error('Error: ' . $e->getMessage());
            
            $this->newLine();
            $this->warn('💡 Troubleshooting tips:');
            $this->line('   • Make sure WAMP/XAMPP MySQL service is running');
            $this->line('   • Check if port 3306 is available');
            $this->line('   • Verify MySQL username/password in .env file');
            $this->line('   • Ensure MySQL server is accessible');
            
            return 1;
        }
    }
}
