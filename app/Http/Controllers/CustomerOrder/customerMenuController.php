<?php

namespace App\Http\Controllers\CustomerOrder;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\MenuItem;
use App\Models\Category;
use App\Models\Banner;

class customerMenuController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('status', 1)->get();
        
        // Get active banners for the customer-facing menu
        $banners = Banner::active()->latest()->get();
        
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

        $items = $query->paginate(20)->withQueryString();

        // For AJAX requests, return only the menu items HTML
        if ($request->ajax()) {
            return view('customerOrder.menu.partials.items-grid', compact('items'))->render();
        }

        return view('customerOrder.menu.index', compact('items', 'categories', 'banners'));
    }
}
