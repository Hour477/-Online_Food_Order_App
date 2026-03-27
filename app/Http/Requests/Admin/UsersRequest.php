<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UsersRequest extends FormRequest
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
        // Get user ID from route parameter (could be string or model)
        $user = $this->route('user');
        $userId = is_object($user) ? $user->id : $user;
        
        // Check if this is an update request (user ID exists)
        $isUpdate = !empty($userId) && $userId !== '0';
        
        if ($isUpdate) {
            // Update: Password is optional
            return [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $userId,
                'password' => 'nullable|min:6|confirmed',
                'role_id' => 'required|exists:roles,id',
                'image' => 'nullable|image|max:2048',
                'phone' => 'nullable|string|max:15',
                'state' => 'in:active,inactive|default:active',
                'city' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
            ];
        } else {
            // Create: Password is required
            return [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
                'role_id' => 'required|exists:roles,id',
                'image' => 'nullable|image|max:2048',
                'phone' => 'nullable|string|max:15',
                'state' => 'in:active,inactive|default:active',
                'city' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
            ];
        }
    }
}