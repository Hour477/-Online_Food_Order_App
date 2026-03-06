<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        MenuItem::insert([
            [
                'category_id' => 1,
                'name' => 'Fried Rice',
                'price' => 3.50,
                'status' => 'available'
            ],
            [
                'category_id' => 2,
                'name' => 'Coca Cola',
                'price' => 1.00,
                'status' => 'available'
            ],
            [
                'category_id' => 3,
                'name' => 'Ice Cream',
                'price' => 2.00,
                'status' => 'available'
            ],
        ]);
    }
}
