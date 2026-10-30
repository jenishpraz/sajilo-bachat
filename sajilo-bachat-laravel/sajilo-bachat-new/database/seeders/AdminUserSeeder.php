<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@example.com');

        if (User::where('email', $email)->exists()) {
            return;
        }

        // Never ship a well-known default password: use ADMIN_PASSWORD or generate one.
        $password = Hash::make('password');
        User::create([
            'name' => env('ADMIN_NAME', 'Site Administrator'),
            'email' => $email,
            'password' => $password,
            'role' => User::ROLE_SUPER_ADMIN,
            'is_active' => true,
            'must_change_password' => true,
        ]);

        $this->command?->warn("Admin account created:  {$email}  /  {$password}");
        $this->command?->warn('You will be asked to choose a new password at first login.');
    }
}
