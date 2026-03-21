<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuItem;
use App\Models\Category;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        
        if ($categories->isEmpty()) {
            $this->command->error('No categories found. Please run CategorySeeder first.');
            return;
        }

        $menuItems = [
            // Appetizers (Category 1)
            ['name' => 'Spring Rolls', 'description' => 'Crispy vegetable spring rolls served with sweet chili sauce', 'price' => 5.99, 'status' => 'available', 'image' => 'menu-items/1773563621_1044847.png'],
            ['name' => 'Chicken Wings', 'description' => 'Spicy grilled chicken wings with special sauce', 'price' => 7.99, 'status' => 'available', 'image' => 'menu-items/1773604762_7.jpg'],
            ['name' => 'Garlic Bread', 'description' => 'Toasted bread with garlic butter and herbs', 'price' => 4.99, 'status' => 'available', 'image' => null],
            ['name' => 'Bruschetta', 'description' => 'Toasted bread with tomatoes, basil, and olive oil', 'price' => 6.99, 'status' => 'available', 'image' => null],
            ['name' => 'Calamari', 'description' => 'Fried squid rings with tartar sauce', 'price' => 8.99, 'status' => 'available', 'image' => null],
            ['name' => 'Soup of the Day', 'description' => 'Ask your server for today\'s soup selection', 'price' => 4.99, 'status' => 'available', 'image' => null],
            ['name' => 'Caesar Salad', 'description' => 'Romaine lettuce with Caesar dressing and croutons', 'price' => 6.99, 'status' => 'available', 'image' => null],
            ['name' => 'Nachos', 'description' => 'Tortilla chips with cheese, salsa, and guacamole', 'price' => 7.99, 'status' => 'available', 'image' => null],
            ['name' => 'Onion Rings', 'description' => 'Beer-battered onion rings with ranch dip', 'price' => 5.99, 'status' => 'available', 'image' => null],
            ['name' => 'Stuffed Mushrooms', 'description' => 'Mushrooms filled with cheese and herbs', 'price' => 6.99, 'status' => 'available', 'image' => null],

            // Main Course (Category 2)
            ['name' => 'Grilled Steak', 'description' => 'Premium beef steak with choice of sides', 'price' => 24.99, 'status' => 'available', 'image' => 'menu-items/1774066885_7.jpg'],
            ['name' => 'Roast Chicken', 'description' => 'Half chicken roasted with herbs and lemon', 'price' => 18.99, 'status' => 'available', 'image' => null],
            ['name' => 'Pork Chops', 'description' => 'Grilled pork chops with apple sauce', 'price' => 19.99, 'status' => 'available', 'image' => null],
            ['name' => 'Lamb Curry', 'description' => 'Traditional lamb curry with aromatic spices', 'price' => 21.99, 'status' => 'available', 'image' => null],
            ['name' => 'Beef Stroganoff', 'description' => 'Tender beef in creamy mushroom sauce', 'price' => 20.99, 'status' => 'available', 'image' => null],
            ['name' => 'Chicken Parmesan', 'description' => 'Breaded chicken with marinara and mozzarella', 'price' => 19.99, 'status' => 'available', 'image' => null],
            ['name' => 'BBQ Ribs', 'description' => 'Slow-cooked ribs with BBQ sauce', 'price' => 23.99, 'status' => 'available', 'image' => null],
            ['name' => 'Duck Confit', 'description' => 'Slow-cooked duck leg with orange glaze', 'price' => 25.99, 'status' => 'available', 'image' => null],
            ['name' => 'Meat Platter', 'description' => 'Assorted grilled meats for two', 'price' => 39.99, 'status' => 'available', 'image' => null],
            ['name' => 'Shepherd\'s Pie', 'description' => 'Ground meat with mashed potato topping', 'price' => 17.99, 'status' => 'available', 'image' => null],

            // Rice & Noodles (Category 3)
            ['name' => 'Fried Rice', 'description' => 'Wok-fried rice with egg and vegetables', 'price' => 9.99, 'status' => 'available', 'image' => null],
            ['name' => 'Pad Thai', 'description' => 'Thai stir-fried noodles with shrimp and peanuts', 'price' => 12.99, 'status' => 'available', 'image' => null],
            ['name' => 'Pho Bo', 'description' => 'Vietnamese beef noodle soup', 'price' => 11.99, 'status' => 'available', 'image' => null],
            ['name' => 'Lo Mein', 'description' => 'Soft noodles with vegetables and meat', 'price' => 10.99, 'status' => 'available', 'image' => null],
            ['name' => 'Biryani', 'description' => 'Aromatic rice with spiced meat', 'price' => 13.99, 'status' => 'available', 'image' => null],
            ['name' => 'Chow Mein', 'description' => 'Crispy noodles with stir-fried vegetables', 'price' => 10.99, 'status' => 'available', 'image' => null],
            ['name' => 'Risotto', 'description' => 'Creamy Italian rice with mushrooms', 'price' => 14.99, 'status' => 'available', 'image' => null],
            ['name' => 'Ramen', 'description' => 'Japanese noodle soup with pork belly', 'price' => 12.99, 'status' => 'available', 'image' => null],
            ['name' => 'Jollof Rice', 'description' => 'West African spiced rice with chicken', 'price' => 11.99, 'status' => 'available', 'image' => null],
            ['name' => 'Udon', 'description' => 'Thick Japanese noodles in broth', 'price' => 11.99, 'status' => 'available', 'image' => null],

            // Soups (Category 4)
            ['name' => 'Tom Yum Soup', 'description' => 'Spicy Thai soup with shrimp', 'price' => 8.99, 'status' => 'available', 'image' => null],
            ['name' => 'French Onion Soup', 'description' => 'Caramelized onion soup with cheese toast', 'price' => 7.99, 'status' => 'available', 'image' => null],
            ['name' => 'Chicken Noodle Soup', 'description' => 'Classic comfort soup with chicken', 'price' => 6.99, 'status' => 'available', 'image' => null],
            ['name' => 'Minestrone', 'description' => 'Italian vegetable soup with pasta', 'price' => 7.99, 'status' => 'available', 'image' => null],
            ['name' => 'Clam Chowder', 'description' => 'Creamy soup with clams and potatoes', 'price' => 8.99, 'status' => 'available', 'image' => null],
            ['name' => 'Hot and Sour Soup', 'description' => 'Asian soup with tofu and mushrooms', 'price' => 6.99, 'status' => 'available', 'image' => null],
            ['name' => 'Gazpacho', 'description' => 'Cold Spanish tomato soup', 'price' => 7.99, 'status' => 'available', 'image' => null],
            ['name' => 'Laksa', 'description' => 'Spicy coconut curry noodle soup', 'price' => 9.99, 'status' => 'available', 'image' => null],
            ['name' => 'Borscht', 'description' => 'Eastern European beet soup', 'price' => 7.99, 'status' => 'available', 'image' => null],
            ['name' => 'Won Ton Soup', 'description' => 'Chinese dumpling soup', 'price' => 7.99, 'status' => 'available', 'image' => null],

            // Salads (Category 5)
            ['name' => 'Greek Salad', 'description' => 'Fresh vegetables with feta cheese and olives', 'price' => 8.99, 'status' => 'available', 'image' => null],
            ['name' => 'Caprese Salad', 'description' => 'Tomatoes, mozzarella, and fresh basil', 'price' => 9.99, 'status' => 'available', 'image' => null],
            ['name' => 'Cobb Salad', 'description' => 'Mixed greens with chicken, bacon, and avocado', 'price' => 11.99, 'status' => 'available', 'image' => null],
            ['name' => 'Asian Salad', 'description' => 'Mixed greens with sesame ginger dressing', 'price' => 8.99, 'status' => 'available', 'image' => null],
            ['name' => 'Quinoa Salad', 'description' => 'Quinoa with roasted vegetables', 'price' => 10.99, 'status' => 'available', 'image' => null],
            ['name' => 'Taco Salad', 'description' => 'Lettuce bowl with ground beef and cheese', 'price' => 11.99, 'status' => 'available', 'image' => null],
            ['name' => 'Spinach Salad', 'description' => 'Baby spinach with strawberries and walnuts', 'price' => 9.99, 'status' => 'available', 'image' => null],
            ['name' => 'Arugula Salad', 'description' => 'Peppery greens with parmesan and lemon', 'price' => 8.99, 'status' => 'available', 'image' => null],
            ['name' => 'Waldorf Salad', 'description' => 'Apples, celery, and walnuts in mayo dressing', 'price' => 8.99, 'status' => 'available', 'image' => null],
            ['name' => 'Nicoise Salad', 'description' => 'Tuna, green beans, and potatoes', 'price' => 12.99, 'status' => 'available', 'image' => null],

            // Grilled Specialties (Category 6)
            ['name' => 'Grilled Salmon', 'description' => 'Atlantic salmon with lemon butter', 'price' => 22.99, 'status' => 'available', 'image' => null],
            ['name' => 'BBQ Chicken', 'description' => 'Half chicken with BBQ glaze', 'price' => 16.99, 'status' => 'available', 'image' => null],
            ['name' => 'Grilled Lamb Chops', 'description' => 'Three lamb chops with mint sauce', 'price' => 26.99, 'status' => 'available', 'image' => null],
            ['name' => 'Fish and Chips', 'description' => 'Beer-battered fish with fries', 'price' => 15.99, 'status' => 'available', 'image' => null],
            ['name' => 'Grilled Shrimp', 'description' => 'Jumbo shrimp with garlic butter', 'price' => 21.99, 'status' => 'available', 'image' => null],
            ['name' => 'Porterhouse Steak', 'description' => 'Large cut steak for sharing', 'price' => 32.99, 'status' => 'available', 'image' => null],
            ['name' => 'Grilled Swordfish', 'description' => 'Meaty fish steak with herbs', 'price' => 23.99, 'status' => 'available', 'image' => null],
            ['name' => 'Kebabs', 'description' => 'Skewered meat with vegetables', 'price' => 18.99, 'status' => 'available', 'image' => null],
            ['name' => 'Grilled Octopus', 'description' => 'Tender octopus with olive oil', 'price' => 24.99, 'status' => 'available', 'image' => null],
            ['name' => 'Surf and Turf', 'description' => 'Steak and lobster tail combination', 'price' => 34.99, 'status' => 'available', 'image' => null],

            // Seafood (Category 7)
            ['name' => 'Lobster Thermidor', 'description' => 'Lobster in creamy cheese sauce', 'price' => 38.99, 'status' => 'available', 'image' => null],
            ['name' => 'Crab Cakes', 'description' => 'Pan-seared crab cakes with remoulade', 'price' => 19.99, 'status' => 'available', 'image' => null],
            ['name' => 'Shrimp Scampi', 'description' => 'Shrimp in garlic white wine sauce', 'price' => 20.99, 'status' => 'available', 'image' => null],
            ['name' => 'Mussels Marinara', 'description' => 'Steamed mussels in tomato sauce', 'price' => 16.99, 'status' => 'available', 'image' => null],
            ['name' => 'Seafood Paella', 'description' => 'Spanish rice with mixed seafood', 'price' => 24.99, 'status' => 'available', 'image' => null],
            ['name' => 'Oysters Rockefeller', 'description' => 'Baked oysters with spinach and herbs', 'price' => 18.99, 'status' => 'available', 'image' => null],
            ['name' => 'Clam Bake', 'description' => 'Mixed clams steamed with vegetables', 'price' => 22.99, 'status' => 'available', 'image' => null],
            ['name' => 'Tuna Tartare', 'description' => 'Raw tuna with avocado and sesame', 'price' => 17.99, 'status' => 'available', 'image' => null],
            ['name' => 'Cioppino', 'description' => 'Italian-American fish stew', 'price' => 23.99, 'status' => 'available', 'image' => null],
            ['name' => 'Blackened Catfish', 'description' => 'Spicy Cajun-style catfish', 'price' => 17.99, 'status' => 'available', 'image' => null],

            // Vegetarian (Category 8)
            ['name' => 'Vegetable Stir Fry', 'description' => 'Wok-tossed seasonal vegetables', 'price' => 12.99, 'status' => 'available', 'image' => null],
            ['name' => 'Eggplant Parmesan', 'description' => 'Breaded eggplant with marinara', 'price' => 14.99, 'status' => 'available', 'image' => null],
            ['name' => 'Veggie Burger', 'description' => 'Plant-based patty with toppings', 'price' => 11.99, 'status' => 'available', 'image' => null],
            ['name' => 'Mushroom Risotto', 'description' => 'Creamy rice with wild mushrooms', 'price' => 15.99, 'status' => 'available', 'image' => null],
            ['name' => 'Vegetable Curry', 'description' => 'Mixed vegetables in coconut curry', 'price' => 13.99, 'status' => 'available', 'image' => null],
            ['name' => 'Stuffed Peppers', 'description' => 'Bell peppers filled with rice and beans', 'price' => 13.99, 'status' => 'available', 'image' => null],
            ['name' => 'Margherita Pizza', 'description' => 'Tomato, mozzarella, and basil', 'price' => 14.99, 'status' => 'available', 'image' => null],
            ['name' => 'Falafel Plate', 'description' => 'Chickpea fritters with tahini', 'price' => 12.99, 'status' => 'available', 'image' => null],
            ['name' => 'Vegetable Lasagna', 'description' => 'Layered pasta with vegetables and cheese', 'price' => 15.99, 'status' => 'available', 'image' => null],
            ['name' => 'Tofu Teriyaki', 'description' => 'Grilled tofu with teriyaki glaze', 'price' => 13.99, 'status' => 'available', 'image' => null],

            // Desserts (Category 9)
            ['name' => 'Chocolate Cake', 'description' => 'Rich chocolate layer cake', 'price' => 6.99, 'status' => 'available', 'image' => null],
            ['name' => 'Tiramisu', 'description' => 'Italian coffee-flavored dessert', 'price' => 7.99, 'status' => 'available', 'image' => null],
            ['name' => 'Cheesecake', 'description' => 'New York style cheesecake', 'price' => 7.99, 'status' => 'available', 'image' => null],
            ['name' => 'Crème Brûlée', 'description' => 'Vanilla custard with caramelized sugar', 'price' => 8.99, 'status' => 'available', 'image' => null],
            ['name' => 'Apple Pie', 'description' => 'Warm apple pie with vanilla ice cream', 'price' => 6.99, 'status' => 'available', 'image' => null],
            ['name' => 'Ice Cream Sundae', 'description' => 'Three scoops with toppings', 'price' => 5.99, 'status' => 'available', 'image' => null],
            ['name' => 'Chocolate Mousse', 'description' => 'Light and airy chocolate dessert', 'price' => 7.99, 'status' => 'available', 'image' => null],
            ['name' => 'Fruit Tart', 'description' => 'Pastry shell with custard and fresh fruit', 'price' => 7.99, 'status' => 'available', 'image' => 'menu-items/1773604734_Fuit-Shake-1-1536x1024.jpg'],
            ['name' => 'Panna Cotta', 'description' => 'Italian cream dessert with berry sauce', 'price' => 7.99, 'status' => 'available', 'image' => null],
            ['name' => 'Brownie à la Mode', 'description' => 'Warm brownie with ice cream', 'price' => 6.99, 'status' => 'available', 'image' => null],

            // Beverages (Category 10)
            ['name' => 'Fresh Orange Juice', 'description' => 'Freshly squeezed orange juice', 'price' => 4.99, 'status' => 'available', 'image' => null],
            ['name' => 'Iced Coffee', 'description' => 'Cold brewed coffee with ice', 'price' => 3.99, 'status' => 'available', 'image' => null],
            ['name' => 'Smoothie', 'description' => 'Mixed berry smoothie', 'price' => 5.99, 'status' => 'available', 'image' => 'menu-items/1773604734_Fuit-Shake-1-1536x1024.jpg'],
            ['name' => 'Lemonade', 'description' => 'Homemade fresh lemonade', 'price' => 3.99, 'status' => 'available', 'image' => null],
            ['name' => 'Milkshake', 'description' => 'Vanilla, chocolate, or strawberry', 'price' => 5.99, 'status' => 'available', 'image' => null],
            ['name' => 'Green Tea', 'description' => 'Hot or iced Japanese green tea', 'price' => 2.99, 'status' => 'available', 'image' => null],
            ['name' => 'Cappuccino', 'description' => 'Espresso with steamed milk foam', 'price' => 4.99, 'status' => 'available', 'image' => null],
            ['name' => 'Bubble Tea', 'description' => 'Tapioca pearl milk tea', 'price' => 5.99, 'status' => 'available', 'image' => null],
            ['name' => 'Coconut Water', 'description' => 'Fresh young coconut water', 'price' => 4.99, 'status' => 'available', 'image' => null],
            ['name' => 'Hot Chocolate', 'description' => 'Rich chocolate with whipped cream', 'price' => 4.99, 'status' => 'available', 'image' => null],
        ];

        $categoryId = 1;
        $itemCount = 0;

        foreach ($menuItems as $index => $menuItem) {
            MenuItem::create([
                'category_id' => $categoryId,
                'name' => $menuItem['name'],
                'description' => $menuItem['description'],
                'price' => $menuItem['price'],
                'status' => $menuItem['status'],
                'image' => $menuItem['image'] ?? null,
            ]);

            $itemCount++;
            
            // Move to next category every 10 items
            if ($itemCount % 10 === 0 && $categoryId < 10) {
                $categoryId++;
            }
        }

        $this->command->info("Successfully created {$itemCount} menu items across {$categoryId} categories.");
    }
}
