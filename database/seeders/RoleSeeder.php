<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1️⃣ Insert roles with slug
        $roles = [
            'Admin',    // id = 1
            'Customer', // id = 2
        ];

        foreach ($roles as $roleName) {
            DB::table('roles')->insert([
                'name' => $roleName,
                'slug' => Str::slug($roleName), // e.g. admin, customer
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2️⃣ Insert some users with 'state' field
        DB::table('users')->insert([
            [
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'role_id' => 1, // Admin
                'password' => Hash::make('password123'),
                'state' => 'active',           // ✅ Add this
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'customer@example.com',
                'role_id' => 2, // Customer
                'password' => Hash::make('password123'),
                'state' => 'active',           // ✅ Add this
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}