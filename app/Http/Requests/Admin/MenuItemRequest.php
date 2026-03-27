<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MenuItemRequest extends FormRequest
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
        
        return [
            //
            "name"          => "required|string|max:120",
            "category_id"   => "required|exists:categories,id",
            "description"   => "nullable|string|max:1000",
            "price"         => "required|numeric|min:0",
            "rating"        => "nullable|numeric|min:0|max:5",
            "popularity"    => "nullable|integer|min:0",
            "status"        => "required|in:available,unavailable",
            "image"         => "nullable|image|mimes:jpeg,png,jpg,gif,webp|max:16384",
        ];
    }
}