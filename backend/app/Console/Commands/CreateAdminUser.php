<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Mail\AdminWelcomeMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create 
                          {--name= : The name of the admin user}
                          {--email= : The email of the admin user}
                          {--password= : The password of the admin user}
                          {--send-email : Send welcome email to the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user account with optional email notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->option('name');
        $email = $this->option('email');
        $password = $this->option('password');
        $sendEmail = $this->option('send-email');

        // If no options provided, create the specific admin user
        if (!$name && !$email && !$password) {
            return $this->createSpecificAdmin();
        }

        // Interactive mode if missing parameters
        if (!$name) {
            $name = $this->ask('What is the admin user\'s name?');
        }
        
        if (!$email) {
            $email = $this->ask('What is the admin user\'s email?');
        }
        
        if (!$password) {
            $password = $this->secret('What is the admin user\'s password?');
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email address');
            return 1;
        }

        // Check if user exists
        if (User::where('email', $email)->exists()) {
            $this->error('A user with this email already exists');
            return 1;
        }

        try {
            // Create user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);

            $this->info("Admin user created successfully!");
            $this->line("ID: {$user->id}");
            $this->line("Name: {$user->name}");
            $this->line("Email: {$user->email}");

            // Send email if requested
            if ($sendEmail) {
                try {
                    Mail::to($user->email)->send(new AdminWelcomeMail($user, $password));
                    $this->info("✅ Welcome email sent to {$user->email}");
                } catch (\Exception $e) {
                    $this->warn("⚠️ User created but email failed: " . $e->getMessage());
                }
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to create admin user: ' . $e->getMessage());
            return 1;
        }
    }

    private function createSpecificAdmin()
    {
        $this->info('Creating specific admin user: Fahmad Iqbal');
        
        // Check if user exists
        if (User::where('email', 'fahmad_iqbal@hotmail.com')->exists()) {
            $this->error('User fahmad_iqbal@hotmail.com already exists');
            return 1;
        }

        try {
            // Create user
            $user = User::create([
                'name' => 'Fahmad Iqbal',
                'email' => 'fahmad_iqbal@hotmail.com',
                'password' => Hash::make('123456'),
            ]);

            $this->info("✅ Admin user created successfully!");
            $this->line("ID: {$user->id}");
            $this->line("Name: {$user->name}");
            $this->line("Email: {$user->email}");
            $this->line("Password: 123456");

            // Send welcome email
            try {
                Mail::to($user->email)->send(new AdminWelcomeMail($user, '123456'));
                $this->info("✅ Welcome email sent to {$user->email}");
                $this->line("");
                $this->line("📧 A beautifully formatted welcome email has been sent with:");
                $this->line("   • Login credentials");
                $this->line("   • Platform overview");
                $this->line("   • Security instructions");
                $this->line("   • Feature highlights");
            } catch (\Exception $e) {
                $this->warn("⚠️ User created but email failed: " . $e->getMessage());
                $this->line("Email configuration may need adjustment in .env file");
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to create admin user: ' . $e->getMessage());
            return 1;
        }
    }
}
