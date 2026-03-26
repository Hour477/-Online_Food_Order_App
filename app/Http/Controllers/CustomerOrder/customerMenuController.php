<?php

namespace App\Http\Controllers\CustomerOrder;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\MenuItem;
use App\Models\Category;
use App\Models\Banner;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;


class customerMenuController extends Controller
{   

    
    public function index(Request $request): View|JsonResponse
    {
        // Validation for filter parameters
        $validated = $request->validate([
            'search' => 'nullable|string|max:100',
            'category' => 'nullable|string',
            'cuisines' => 'nullable|string', // IDs separated by comma or array
            'sort' => 'nullable|string|in:price_asc,price_desc,name_asc,fastest,rating,relevance',
            'popular_like' => 'nullable|boolean',
            'top_rated' => 'nullable|boolean',
            'top_dishes' => 'nullable|boolean',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'page' => 'nullable|integer'
        ]);

        /** @var \Illuminate\Database\Eloquent\Collection<int, Category> $categories */
        $categories = Category::query()->where('status', 1)->get();
        
        /** @var \Illuminate\Database\Eloquent\Collection<int, Banner> $banners */
        /** @phpstan-ignore-next-line */
        $banners = Banner::active()->latest()->get();
        
        $query = MenuItem::query()->where('status', 'available');

        // Apply Search
        if ($request->filled('search')) {
            /** @phpstan-ignore-next-line */
            $query->search((string)$request->input('search'));
        }

        // Apply Category (single)
        if ($request->filled('category') && $request->input('category') !== 'all') {
            $query->where(function($q) use ($request) {
                $categoryName = (string)$request->input('category');
                
                if (strtolower($categoryName) === 'uncategorized' || strtolower($categoryName) === 'null') {
                    $q->whereNull('category_id');
                } else {
                    $q->whereHas('category', function($subQ) use ($categoryName) {
                        $subQ->where('name', $categoryName);
                    });
                }
            });
        }

        // Apply Cuisines (multiple category IDs)
        if ($request->filled('cuisines')) {
            $cuisinesStr = $request->input('cuisines');
            $cuisines = is_array($cuisinesStr) ? $cuisinesStr : explode(',', (string)$cuisinesStr);
            /** @phpstan-ignore-next-line */
            $query->inCategory($cuisines);
        }

        // Apply Price Range
        if ($request->filled('min_price') || $request->filled('max_price')) {
            /** @phpstan-ignore-next-line */
            $query->inPriceRange($request->input('min_price'), $request->input('max_price'));
        }

        // Apply Quick Filters
        if ($request->boolean('popular_like')) {
            /** @phpstan-ignore-next-line */
            $query->popularLike();
        }
        
        if ($request->boolean('top_rated')) {
            /** @phpstan-ignore-next-line */
            $query->topRated();
        }

        if ($request->boolean('top_dishes')) {
            /** @phpstan-ignore-next-line */
            $query->topDishes();
        }

        // Sorting logic
        match($validated['sort'] ?? 'relevance') {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name_asc'   => $query->orderBy('name', 'asc'),
            'fastest'    => $query->orderBy('created_at', 'desc'),
            'rating'     => $query->orderBy('rating', 'desc'),
            default      => $query->latest(),
        };

        $items = $query->paginate(100)->withQueryString();

        // AJAX response for seamless filtering/pagination
        if ($request->ajax()) {
            return response()->json([
                'html' => view('customerOrder.menu.partials.items-grid', compact('items'))->render(),
                'pagination' => [
                    'current_page' => $items->currentPage(),
                    'last_page' => $items->lastPage(),
                    'total' => $items->total(),
                    'has_more' => $items->hasMorePages(),
                    'next_page_url' => $items->nextPageUrl()
                ]
            ]);
        }

        return view('customerOrder.menu.index', compact('items', 'categories', 'banners'));
    }
}
