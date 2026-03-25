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

        return [
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $customer->user_id,
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
        ];
    }

        protected function prepareForValidation()
        {
            $this->mergeIfMissing([
                'name' => 'Walk-in',
            ]);
        }
        
}