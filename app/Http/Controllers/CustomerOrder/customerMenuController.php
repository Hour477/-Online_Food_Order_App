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

        // Filter by category (from URL param or sidebar checkboxes)
        if ($request->filled('category') && $request->category !== 'all') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        if ($request->filled('cuisines')) {
            $cuisines = is_array($request->cuisines) ? $request->cuisines : explode(',', $request->cuisines);
            $query->whereIn('category_id', $cuisines);
        }

        // Quick filters (Example logic as columns might not exist yet)
        if ($request->boolean('rating_4_5')) {
            // $query->where('rating', '>=', 4.5); 
            // For now, let's just simulate or ignore if column missing, 
            // but the logic should be here.
        }
        
        if ($request->boolean('free_delivery')) {
            // $query->where('is_free_delivery', true);
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
            'fastest'    => $query->orderBy('created_at', 'desc'), // Simulate fastest
            'rating'     => $query->orderBy('id', 'desc'), // Simulate top rated
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
