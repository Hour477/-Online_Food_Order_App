<?php
namespace App\Http\Requests\Admin;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest   
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {   
        $customer = $this->route('customer');
        $userId = $customer ? $customer->user_id : null;

        return [
            'name'     => 'required|string|max:255',
            'email'    => 'nullable|email|max:255|unique:users,email,' . $userId,
            'phone'    => 'nullable|string|max:20',
            'password' => 'nullable|min:6|max:255',
            'state'    => 'nullable|string|max:255',
            'address'  => 'nullable|string|max:255',
            'city'     => 'nullable|string|max:255',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ];
    }

        protected function prepareForValidation()
        {
            $this->mergeIfMissing([
                'name' => 'Walk-in',
            ]);
        }
        
}