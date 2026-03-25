<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerRequest;
use App\Models\Customer;
use App\Models\User;
use App\Services\CustomerService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    protected  $customerService;
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

    public function store(CustomerRequest $request)
    {
        // 1. Set the default if 'name' is missing
        $request->mergeIfMissing(['name' => 'Walk-in']); 

        $customer = $this->customerService->store($request->all());

        // 4. Create user with role_id = 5 (Customer)
            $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone ?? null,
            'password' => Hash::make($request->password ?? 'password123'),
            'role_id' => $customer->role_id, // Customer role
            'image' => $customer->image,
        ]);

      

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer created successfully!');
    }

    public function show(string $id)
    {
        //
        $customer = Customer::withTrashed()->findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(string $id)
    {
        //
        $customer = Customer::withTrashed()->findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        $this->customerService->update(
            $customer,
            $request->validated(),
            $request->file('image')
        );
        ToastMagic::success('Updated Info Customer Successfully!');

        return redirect()->route('admin.customers.index');
            
    }

    public function destroy(string $id)
    {
        //
        $customer = Customer::findOrFail($id);
        $customer->delete();
        ToastMagic::success('Deleted Customer Successfully!');
        return redirect()->route('admin.customers.index');
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