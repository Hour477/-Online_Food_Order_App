<?php

namespace App\Http\Controllers\CustomerOrder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Helpers\ImageHelper;

class CustomerProfileController extends Controller
{
    /**
     * Display the customer's profile.
     */
    public function show()
    {
        $user = Auth::user();
        return view('customerOrder.profile.show', compact('user'));
    }

    /**
     * Show the form for editing the customer's profile.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('customerOrder.profile.edit', compact('user'));
    }

    /**
     * Update the customer's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        // use imageHelper if it exists
        if ($request->hasFile('image')) {
            $user->image = ImageHelper::update(
                $request->file('image'),
                $user->image,
                'users',
            );
        }
        
        $user->save();
        return redirect()->route('customerOrder.profile.show')->with('success', __('app.profile_updated'));
    }
}
