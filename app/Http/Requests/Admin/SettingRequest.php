<?php

namespace App\Http\Requests\Admin;



use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $settings = $this->route('setting');

        return [

            'resturant_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'logo' => [
                'image',
                'mimes:png,jpg,jpeg,svg',
                'max:2048'
            ],
            'favicon' => [
                'image',
                'mimes:png,jpg,jpeg,svg',
                'max:2048'
            ],

            'resturant_phone' => [
                'nullable',
                'string',
                'max:255',
            ],
            'resturant_email' => [
                'nullable',
                'email',
                'string',
                'max:255',
            ],
            'resturant_address' => [
                'nullable',
                'string',
                'max:255',
            ],


        ];
    }
}
