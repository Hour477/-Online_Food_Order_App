<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Devrabiul\ToastMagic\Facades\ToastMagic;
use App\Helpers\ImageHelper;
use App\Http\Requests\Admin\MenuItemRequest;
use App\Services\MenuItemService;
use PhpParser\Node\Expr\FuncCall;

class MenuItemController extends Controller
{   

    protected $menuItemService;
    public function __construct(MenuItemService $menuItemService){
        $this->menuItemService = $menuItemService;
    }
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
                        ->orwhere('price', 'LIKE', '%' . $search . '%');
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
            ->latest()->paginate($request->get('per_page', 8))
            ->withQueryString();



        return view('admin.menu_items.index', compact('menu_items', 'categories'));
    }


    public function create()
    {
        $categories = Category::all();
        return view('admin.menu_items.create', compact('categories'));
    }

    public function store(MenuItemRequest $request)
    {
       
        $data = $request->validated();
        $this->menuItemService->store($data);
        ToastMagic::success('Menu item created successfully');
        return redirect()->route('admin.menu_items.index');
    }

    public function show(string $id)
    {
        //
        $menu_item = MenuItem::findOrFail($id);
        return view('admin.menu_items.show', compact('menu_item'));
    }

    public function edit($id)
    {

        $categories = Category::all();
        $menu_item = MenuItem::with('category')
            ->select('id', 'name', 'description', 'price', 'rating', 'popularity', 'status', 'image', 'category_id')
            ->findOrFail($id);
        return view('admin.menu_items.edit', compact('menu_item', 'categories'));
    }

    public function update(MenuItemRequest $request, string $id) 
    {
       
        $data = $request->validated();
        $this->menuItemService->update($id, $data);
        ToastMagic::success('Menu item updated successfully');
        return redirect()->route('admin.menu_items.index');
    }

    public function destroy(string $id)
    {
        $this->menuItemService->destroy($id);
        ToastMagic::success('Menu item deleted successfully');
        return redirect()->route('admin.menu_items.index');
    }
    
    
}