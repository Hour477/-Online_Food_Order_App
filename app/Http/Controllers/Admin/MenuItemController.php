<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $menu_items = MenuItem::with('category')
            ->latest()
            ->paginate(15); 

        return view('menu_items.index', compact('menu_items', 'categories'));
    }
    
    public function create()
    {
        $categories = Category::all();
        return view('menu_items.create' , compact('categories'));
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
            $filename = time().'_'.preg_replace('/[^A-Za-z0-9\.\-_]/', '_', $file->getClientOriginalName());
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
    }

    public function edit(string $id)
    {
        $menu_items = MenuItem::findOrFail($id);
        $categories = Category::all();
        return view('menu_items.edit', compact('menu_items', 'categories'));
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
            
            if($menuItem->image) {
                Storage::disk('public')->delete($menuItem->image);
            }
            $file = $request->file('image');
            $filename = time().'_'.preg_replace('/[^A-Za-z0-9\.\-_]/', '_', $file->getClientOriginalName());
            $path = $file->storeAs('menu-items', $filename, 'public');
            $validated['image'] = $path;
        }

        $menuItem->update($validated);

        return redirect()->route('menu_items.index')
            ->with('success', 'Menu item updated successfully!');
    }

    public function destroy(string $id)
    {
        $item = MenuItem::findOrFail($id);
        if($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();
        return redirect()->route('menu_items.index')
            ->with('success', 'Menu item deleted successfully!');
    }
}