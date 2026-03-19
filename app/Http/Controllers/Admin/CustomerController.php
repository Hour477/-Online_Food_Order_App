<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\CustomerService;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    protected $customerService;
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }
    
    public function index()
    {
        $customers = $this->customerService->list();
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
            'email' => 'nullable|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 3. Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('users', 'public');
        }

        // 4. Create user with role_id = 5 (Customer)
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password'] ?? 'password123'),
            'role_id' => 5, // Customer role
            'image' => $imagePath,
        ]);

        // 5. Create customer linked to user
        Customer::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ]);

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
        // Get top customers by total orders and spending
        $topCustomers = $this->customerService->best();
        return view('admin.customers.best', compact('topCustomers'));
    }

    public function recent()
    {
        //
        return view('admin.customers.recent');
    }
}