<?php 


namespace App\Services;
use App\Helpers\ImageHelper;
use App\Models\Customer;
use Devrabiul\ToastMagic\Facades\ToastMagic;

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
            ->paginate(5);
        return $topCustomers;
    }

    public function store(array $data)
    {
        try{
              if (isset($data['image'])) {
                    $data['image'] = ImageHelper::upload($data['image'], 'customers');
                }
                
                $customer = Customer::create($data);
                $customer->save();
                return $customer;
                
       }catch(\Exception $e){
        ToastMagic::error($e->getMessage());
        return $e;
       }
    }

    
    public function update(Customer $customer, array $data, $file = null)
    {

        try{
            $user = $customer->user;
            // Image
            if ($file) {
                $data['image'] = ImageHelper::update(
                    $file,
                    $user->image ?? null,
                    'users'
                );
            }

            // Update user
            $userData = [
                'name'  => $data['name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
            ];
            
            if (isset($data['image'])) {
                $userData['image'] = $data['image'];
            }
            
            $user->update($userData);

            // Update customer
            $customer->update([
                'name'  => $data['name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
            ]);

            return $customer;
            
        }catch(\Exception $e){
            ToastMagic::error($e->getMessage());
            return $e;
        }

    }

} 
