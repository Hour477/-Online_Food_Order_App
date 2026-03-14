<?php

namespace App\Http\Controllers\CustomerOrder;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;

class customerMenuController extends Controller
{
    /**
     * All menu items (in a real app this would come from the DB / Eloquent model)
     */
    private function allItems(): array
    {
        return [
            // ── MEALS ──────────────────────────────────────────────────
            ['id'=>1,'category'=>'meals','name'=>'Classic Beef Burger','description'=>'Juicy 200g beef patty, aged cheddar, house pickles, secret sauce on a brioche bun.','price'=>12.99,'image'=>'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&q=80','popular'=>true],
            ['id'=>2,'category'=>'meals','name'=>'Margherita Pizza','description'=>'Thin crust, San Marzano tomatoes, fresh mozzarella, basil, extra-virgin olive oil.','price'=>14.99,'image'=>'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&q=80','popular'=>true],
            ['id'=>3,'category'=>'meals','name'=>'Grilled Salmon Bowl','description'=>'Atlantic salmon fillet, jasmine rice, edamame, avocado, teriyaki glaze.','price'=>16.49,'image'=>'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=600&q=80'],
            ['id'=>4,'category'=>'meals','name'=>'Chicken Caesar Wrap','description'=>'Grilled chicken breast, romaine lettuce, parmesan, croutons, classic Caesar dressing.','price'=>10.99,'image'=>'https://images.unsplash.com/photo-1550304943-4f24f54ddde9?w=600&q=80'],
            ['id'=>5,'category'=>'meals','name'=>'Pasta Carbonara','description'=>'Al dente spaghetti, guanciale, egg yolk, pecorino romano, black pepper.','price'=>13.49,'image'=>'https://images.unsplash.com/photo-1612874742237-6526221588e3?w=600&q=80'],
            ['id'=>6,'category'=>'meals','name'=>'Veggie Buddha Bowl','description'=>'Roasted chickpeas, quinoa, sweet potato, kale, tahini dressing, pumpkin seeds.','price'=>11.99,'image'=>'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600&q=80'],
            // ── DRINKS ─────────────────────────────────────────────────
            ['id'=>7,'category'=>'drinks','name'=>'Mango Lassi','description'=>'Creamy blend of ripe mango, yogurt, milk, cardamom, a touch of rose water.','price'=>5.49,'image'=>'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&q=80','popular'=>true],
            ['id'=>8,'category'=>'drinks','name'=>'Cold Brew Coffee','description'=>'24-hour steeped single-origin beans, served over ice with oat milk.','price'=>4.99,'image'=>'https://images.unsplash.com/photo-1551024709-8f23befc6f87?w=600&q=80'],
            ['id'=>9,'category'=>'drinks','name'=>'Watermelon Mint Cooler','description'=>'Fresh watermelon juice, lime, mint, sparkling water. Perfectly refreshing.','price'=>4.49,'image'=>'https://images.unsplash.com/photo-1622597467836-f3285f2131b8?w=600&q=80'],
            ['id'=>10,'category'=>'drinks','name'=>'Classic Lemonade','description'=>'Hand-squeezed lemons, cane sugar syrup, still or sparkling water, lemon zest.','price'=>3.99,'image'=>'https://images.unsplash.com/photo-1621263764928-df1444c5e859?w=600&q=80'],
            // ── DESSERTS ───────────────────────────────────────────────
            ['id'=>11,'category'=>'desserts','name'=>'Chocolate Lava Cake','description'=>'Warm dark chocolate fondant with a molten centre, served with vanilla ice cream.','price'=>7.99,'image'=>'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=600&q=80','popular'=>true],
            ['id'=>12,'category'=>'desserts','name'=>'New York Cheesecake','description'=>'Dense, creamy classic cheesecake on a graham cracker crust, berry compote.','price'=>6.99,'image'=>'https://images.unsplash.com/photo-1524351199678-941a58a3df50?w=600&q=80'],
            ['id'=>13,'category'=>'desserts','name'=>'Crème Brûlée','description'=>'Silky vanilla custard under a perfectly caramelised sugar crust. Timeless.','price'=>6.49,'image'=>'https://images.unsplash.com/photo-1470124182917-cc6e71b22ecc?w=600&q=80'],
            ['id'=>14,'category'=>'desserts','name'=>'Tiramisu','description'=>'Espresso-soaked ladyfingers, mascarpone cream, cocoa powder dusting. Classic Italian.','price'=>6.99,'image'=>'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?w=600&q=80'],
        ];
    }

    public function index(Request $request)
    {
        $items = collect($this->allItems());

        // Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $items = $items->where('category', $request->category)->values();
        }

        // Search
        if ($request->filled('search')) {
            $q = strtolower($request->search);
            $items = $items->filter(fn($i) =>
                str_contains(strtolower($i['name']), $q) ||
                str_contains(strtolower($i['description']), $q)
            )->values();
        }

        // Sort
        $items = match($request->sort) {
            'price_asc'  => $items->sortBy('price')->values(),
            'price_desc' => $items->sortByDesc('price')->values(),
            'name_asc'   => $items->sortBy('name')->values(),
            default      => $items,
        };

        return view('customerOrder.menu.index', compact('items'));
    }
}
