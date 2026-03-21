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
        ]);
        

        return $users;
    }

    /**
     * Handle user update logic.
     */
    public function updateUser(array $data, string $id)
    {
        $user = User::find($id);

        // Handle image upload - delete old image if uploading new one
        if (!empty($data['image'])) {
            // Delete old image if exists
            if ($user->image) {
                try {
                    $oldImagePath = 'users/' . $user->image;
                    if (Storage::disk('public')->exists($oldImagePath)) {
                        Storage::disk('public')->delete($oldImagePath);
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to delete old user image: ' . $e->getMessage());
                }
            }

            // Upload new image
            $data['image'] = UploadImageHelper::upload('users/', 'png', $data['image']);
        } else {
            unset($data['image']);
        }

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
