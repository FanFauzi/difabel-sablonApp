<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // Create some sample activity logs
        \App\Models\ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'login',
            'description' => 'Admin login ke sistem',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'created_at' => now()->subHours(2)
        ]);

        \App\Models\ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'create',
            'model_type' => 'User',
            'model_id' => $admin->id,
            'description' => 'Membuat akun admin baru',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'created_at' => now()->subHours(3)
        ]);

        \App\Models\ActivityLog::create([
            'action' => 'create',
            'model_type' => 'Product',
            'model_id' => 1,
            'description' => 'Menambahkan produk baru: Kaos Custom',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'created_at' => now()->subHours(4)
        ]);
    }
}
