<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailCommand extends Command
{
    /**
     * The signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Sends a test email using the configured mail settings.';

    /**
     * Execute the command.
     */
    public function handle()
    {
        // $this->info('Dumping mail configuration being used:');
        // $this->line('Mailer:   ' . config('mail.default'));
        // $this->line('Host:     ' . config('mail.mailers.smtp.host'));
        // $this->line('Port:     ' . config('mail.mailers.smtp.port'));
        // $this->line('Username: ' . config('mail.mailers.smtp.username'));
        // $this->line('Password: ' . (config('mail.mailers.smtp.password') ? '****** (set)' : 'null (not set!)'));
        // $this->line('Encryption: ' . config('mail.mailers.smtp.encryption'));
        // $this->newLine();

        $this->info('Attempting to send a test email to Mailtrap...');
        try {
            Mail::raw('This is a test email from an Artisan command.', function ($message) {
                
                // Puedes dejar tu email o volver a poner uno de prueba
                $message->to('test@example.com')->subject('Artisan Mail Test');

            });
            $this->info('✅ Email sent successfully! Check your email provider (Brevo, Mailtrap, etc.).');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Failed to send email.');
            $this->error('Error Message: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
