<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Devrabiul\ToastMagic\Facades\ToastMagic;

class MenuItemController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $price = $request->get('price');

        $categories = Category::all();


        $query = MenuItem::with('category')

            ->when($search, function ($query, $search) {
                // using keyword $search for call in .blade.php
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orwhere('price', 'LIKE', '%'. $search .'%');
                });
            })
            // using keywork $status
            
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                if ($status === 'available') {
                    $query->where('status', 1);
                }

                if ($status === 'unavailable') {
                    $query->where('status', 0);
                }
            });
            // price 
         
            
            

            
    
        $menu_items = $query
            ->with('category')
            ->latest()->paginate($request->get('per_page',5))
            ->withQueryString();
            
            

        return view('menu_items.index', compact('menu_items', 'categories'));

    }


    public function create()
    {
        $categories = Category::all();
        return view('menu_items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:120',
            'category_id'   => 'required|exists:categories,id',
            'description'   => 'nullable|string|max:1000',
            'price'         => 'required|numeric|min:0',
            'status'        => 'required|in:available,unavailable',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $validated;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            // preserve original name with a timestamp prefix to avoid collisions
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\.\-_]/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('menu-items', $filename, 'public');
            $data['image'] = $path;
        }

        MenuItem::create($data);

        return redirect()->route('menu_items.index')
            ->with('success', 'Menu item created successfully!');
    }

    public function show(string $id)
    {
        //
        $menu_item = MenuItem::findOrFail($id);
        return view('menu_items.show', compact('menu_item'));
    }

    public function edit($id)
    {
        
        $categories = Category::all();
        $menu_item = MenuItem::with('category')
        
            ->select('id', 'name', 'description', 'price', 'status', 'image', 'category_id')
            ->findOrFail($id);
        
            
        return view('menu_items.edit', compact('menu_item', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $menuItem = MenuItem::findOrFail($id);

        $validated = $request->validate([
            'name'          => 'required|string|max:120',
            'category_id'   => 'required|exists:categories,id',
            'description'   => 'nullable|string|max:1000',
            'price'         => 'required|numeric|min:0',
            'status'        => 'required|in:available,unavailable',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:16384',
        ]);

        if ($request->hasFile('image')) {

            if ($menuItem->image) {
                Storage::disk('public')->delete($menuItem->image);
            }
            $file = $request->file('image');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9\.\-_]/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('menu-items', $filename, 'public');
            $validated['image'] = $path;
        }

        $menuItem->update($validated);
        ToastMagic::success('Menu item updated successfully');


        return redirect()->route('menu_items.index')
            ->with('success', 'Menu item updated successfully!');
    }

    public function destroy(string $id)
    {
        $item = MenuItem::findOrFail($id);
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();
        ToastMagic::success('Menu item deleted successfully');
        return redirect()->route('menu_items.index')
            ->with('success', 'Menu item deleted successfully!');
    }
}
