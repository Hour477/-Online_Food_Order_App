<?php

namespace App\Services;

use App\Helpers\UploadImageHelper;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



class UserServices
{
    /**
     * Handle user creation logic.
     */
    public function createUser(array $data)
    {
        // Handle image upload
        if (!empty($data['image'])) {
            $data['image'] = UploadImageHelper::upload('users/', 'png', $data['image']);
        }
        

        // hash password
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $users =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role_id' => $data['role_id'] ?? null,
            'image' => $data['image'] ?? null,
        ]);
        
        return $users;
    
    
    }

    /**
     * Handle user update logic.
     */
    public function updateUser(User $user, array $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $user->update($data);
    }

    /**
     * Delete a user.
     */
    public function deleteUser(User $user)
    {
        return $user->delete();
    }

    /**
     * Search and paginate users.
     */
    public function listUsers()
    {

    $search = request('search');
    
        return User::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(5)
            ->withQueryString();
    }
}
