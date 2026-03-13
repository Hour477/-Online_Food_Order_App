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
            'Admin',
            'Manager',
            'Cashier',
            'Waiter',
            'Customer',
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
                'role_id' => 2, // admin
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'janesmith@example.com',
                'role_id' => 5, // Customer
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
