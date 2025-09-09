<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1) Usuario admin básico (cámbialo si quieres)
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'              => 'Super Admin',
                'password'          => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );

        // 2) Tenant inicial (si creaste el seeder InitialTenantSeeder)
        if (class_exists(\Database\Seeders\InitialTenantSeeder::class)) {
            $this->call(\Database\Seeders\InitialTenantSeeder::class);
            $this->call(\Database\Seeders\AttachAdminToTenantSeeder::class);

        }
    }
}
