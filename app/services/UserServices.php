<?php

namespace App\Services;

use App\Helpers\UploadImageHelper;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ImageHelper;



class UserServices
{
    /**
     * Handle user creation logic.
     */
    public function createUser(array $data, $request)
    {
        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = ImageHelper::upload($request->file('image'), 'users');
        } else {
            $data['image'] = null;
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
            'phone' => $data['phone'] ?? null,
            'state' => $data['state'] ?? 'active',
            'city' => $data['city'] ?? null,
            'address' => $data['address'] ?? null,
            
        ]);
        

        return $users;
    }

    /**
     * Handle user update logic.
     */
    public function updateUser(array $data, string $id, $request)
    {
        $user = User::findOrFail($id); // safer

        // Handle image update
        if ($request->hasFile('image')) {
            $data['image'] = ImageHelper::update(
                $request->file('image'),
                $user->image,
                'users'
            );
        }

        // Handle password
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return $user; // better return updated model
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
