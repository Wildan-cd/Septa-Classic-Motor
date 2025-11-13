<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing users
        DB::table('users')->truncate();
        
        // Create Admin User
        DB::table('users')->insert([
            'name' => 'Admin Septa',
            'email' => 'admin@septaclassicmotor.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'admin',
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Create Customer User (for testing)
        DB::table('users')->insert([
            'name' => 'Customer Test',
            'email' => 'customer@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'customer',
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "✅ Users created successfully!\n";
        echo "📧 Admin: admin@septaclassicmotor.com / password\n";
        echo "📧 Customer: customer@test.com / password\n";
    }
}