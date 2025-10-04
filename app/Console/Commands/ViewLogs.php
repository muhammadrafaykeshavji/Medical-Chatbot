<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ViewLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:view {--lines=50}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'View recent log entries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (!file_exists($logFile)) {
            $this->error('Log file not found');
            return;
        }
        
        $lines = $this->option('lines');
        $command = "tail -n {$lines} \"{$logFile}\"";
        
        if (PHP_OS_FAMILY === 'Windows') {
            $command = "powershell -Command \"Get-Content '{$logFile}' -Tail {$lines}\"";
        }
        
        $output = shell_exec($command);
        $this->info($output);
    }
}
