<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');


        $query = Category::select("id", "name", "description", "status")
            ->when($search, function ($query, $search) {
                // using keyword $search for call in .blade.php
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');

                });
            })
            // using keywork $status

            ->when($status !== null && $status !== '', function ($query) use ($status) {
                if ($status === 'active') {
                    $query->where('status', 1);
                }

                if ($status === 'inactive') {
                    $query->where('status', 0);
                }
            });

        $categories = $query->latest()->paginate($request->get('per_page', 10))
            ->withQueryString();


        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'status'      => 'required|boolean',
        ]);

        Category::create($validated);
        ToastMagic::success('categories created successfully');
        return redirect()->route('admin.categories.index');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'status'      => 'required|boolean',
        ]);

        $category = Category::findOrFail($id);
        $category->update($validated);
        ToastMagic::success('categories created successfully');
        return redirect()->route('admin.categories.index');

    }

    public function destroy(string $id)
    {
        Category::destroy($id);
        ToastMagic::success('Delete category successfully');
        return redirect()->route('admin.categories.index');

    }
}
