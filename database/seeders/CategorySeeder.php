<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Appetizers',
                'description' => 'Start your meal right with our delicious appetizers',
                'status' => 1,
            ],
            [
                'name' => 'Main Course',
                'description' => 'Hearty and satisfying main dishes',
                'status' => 1,
            ],
            [
                'name' => 'Rice & Noodles',
                'description' => 'Traditional rice and noodle dishes',
                'status' => 1,
            ],
            [
                'name' => 'Soups',
                'description' => 'Warm and comforting soups',
                'status' => 1,
            ],
            [
                'name' => 'Salads',
                'description' => 'Fresh and healthy salad options',
                'status' => 1,
            ],
            [
                'name' => 'Grilled Specialties',
                'description' => 'Delicious grilled meats and seafood',
                'status' => 1,
            ],
            [
                'name' => 'Seafood',
                'description' => 'Fresh catch of the day',
                'status' => 1,
            ],
            [
                'name' => 'Vegetarian',
                'description' => 'Plant-based delicious options',
                'status' => 1,
            ],
            [
                'name' => 'Desserts',
                'description' => 'Sweet treats to end your meal',
                'status' => 1,
            ],
            [
                'name' => 'Beverages',
                'description' => 'Refreshing drinks and beverages',
                'status' => 1,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
