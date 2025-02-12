<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Get admin credentials from environment variables or use defaults
        $adminName = env('ADMIN_NAME', 'Administrator');
        $adminEmail = env('ADMIN_EMAIL', 'admin@admin.com');
        $adminPassword = env('ADMIN_PASSWORD', 'brucegwapo');

        // Check if admin user exists
        $admin = User::where('email', $adminEmail)->first();

        if (!$admin) {
            User::create([
                'name' => $adminName,
                'email' => $adminEmail,
                'password' => Hash::make($adminPassword),
                'role' => 'admin',
            ]);
        } else {
            // Update existing admin user's role if needed
            $admin->update([
                'role' => 'admin',
                'name' => $adminName
            ]);
        }
    }
}
