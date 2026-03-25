<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuItem;
use App\Models\Category;

class LargeMenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        if ($categories->isEmpty()) {
            $categories = collect([Category::create(['name' => 'General', 'status' => 1])]);
        }

        $items = [];
        for ($i = 1; $i <= 1000; $i++) {
            $items[] = [
                'category_id' => $categories->random()->id,
                'name' => "Menu Item $i",
                'description' => "Description for item $i. This is a delicious dish with many ingredients.",
                'price' => rand(5, 50) + (rand(0, 99) / 100),
                'rating' => rand(30, 50) / 10,
                'popularity' => rand(0, 500),
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($items) >= 100) {
                MenuItem::insert($items);
                $items = [];
            }
        }
        if (count($items) > 0) {
            MenuItem::insert($items);
        }
    }
}
