<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        // 1. Set the default if 'name' is missing
        $request->mergeIfMissing(['name' => 'Walk-in']); 

        // 2. Run validation (now 'name' is guaranteed to exist)
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],   
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            
        ]);

        Customer::create($validated);
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer created successfully!');
    }

    public function show(string $id)
    {
        //
        $customer = Customer::findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(string $id)
    {
        //
        $customer = Customer::findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, string $id)
    {
        //
        $request->mergeIfMissing(['name' => 'Walk-in']);
        $validated = $request->validate([
            // Name can defualt to Waik-in if not provided
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($validated);
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer updated successfully!');

    }

    public function destroy(string $id)
    {
        //
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully!');
    }

    public function best()
    {
        //
        return view('admin.customers.best');
    }

    public function recent()
    {
        //
        return view('admin.customers.recent');
    }
}