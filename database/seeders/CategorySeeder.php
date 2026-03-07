<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    
        Category::insert([
            ['name' => 'Food', 'status' => 1],
            ['name' => 'Drink', 'status' => 1],
            ['name' => 'Dessert', 'status' => 1],
        ]);
    }
    
}
