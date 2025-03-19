<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create';
    protected $description = 'Create or update the admin user';

    public function handle()
    {
        $admin = User::where('email', 'admin@isi.com')->first();

        if (!$admin) {
            User::create([
                'nom' => 'Admin',
                'prenom' => 'System',
                'email' => 'admin@isi.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]);
            $this->info('Admin user created successfully.');
        } else {
            $admin->update([
                'role' => 'admin',
            ]);
            $this->info('Admin user updated successfully.');
        }
    }
} 