<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('log:clear', function() {
    if ($this->confirm('Do you wish to continue?')) {
        exec('echo "" > ' . storage_path('logs/laravel.log'));
        $this->info('Logs have been cleared');        
    }
})->describe('Clear Laravel log');

Artisan::command('logs:clear', function() {
    exec('echo "" > ' . storage_path('logs/laravel.log'));
    $this->info('Logs have been cleared');
})->describe('Clear log files');
