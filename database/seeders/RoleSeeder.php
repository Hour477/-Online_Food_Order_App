<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        //
       // 1️⃣ Insert roles with slug
        $roles = [
            'Admin',    // id = 1
            'Manager',  // id = 2
            'Cashier',  // id = 3
            'Waiter',   // id = 4
            'Customer', // id = 5
        ];

        foreach ($roles as $roleName) {
            DB::table('roles')->insert([
                
                'name' => $roleName,
                'slug' => Str::slug($roleName), // e.g. admin, manager
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2️⃣ Insert some customers in users table
        DB::table('users')->insert([
            [
                'name' => 'Super Admin',
                'email' => 'admin@admin.com',
                'role_id' => 1, // Admin
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'customer@example.com',
                'role_id' => 5, // Customer
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
