<?php 


namespace App\Services;
use App\Models\Customer;

class CustomerService
{
    public function list()
    {
        $search = request()->query('search');
        $customers = Customer::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(10);
        return $customers;
    }


    public function best()
    {
        $topCustomers = Customer::withCount('orders')
            ->withSum('orders', 'total_amount')
            ->having('orders_count', '>', 0)
            ->orderByDesc('orders_count')
            ->orderByDesc('orders_sum_total_amount')
            ->paginate(3);
        return $topCustomers;
    }
} 
