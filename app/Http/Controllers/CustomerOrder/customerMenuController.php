<?php

namespace App\Http\Controllers\CustomerOrder;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\MenuItem;
use App\Models\Category;

class customerMenuController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('status', 1)->get();
        
        $query = MenuItem::query()->where('status', 'available');

        // Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        // Search
        if ($request->filled('search')) {
            $q = '%' . $request->search . '%';
            $query->where(function($query) use ($q) {
                $query->where('name', 'like', $q)
                      ->orWhere('description', 'like', $q);
            });
        }

        // Sort
        match($request->sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name_asc'   => $query->orderBy('name', 'asc'),
            default      => $query->latest(),
        };

        $items = $query->get();

        return view('customerOrder.menu.index', compact('items', 'categories'));
    }
}
