<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
            $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'The provided credentials do not match our records.'])
                ->onlyInput('email');
        }

        // Regenerate AFTER successful auth (Laravel does this automatically in many cases)
        $request->session()->regenerate();

        return redirect()->intended($this->redirectToFor(Auth::user()));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/menu')->with('success', 'Logged out successfully');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $customerRoleId = Role::where('slug', 'customer')->value('id');
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id'  => $customerRoleId,
        ]);

        Auth::login($user);

        return redirect()->intended($this->redirectToFor($user))
            ->with('success', 'Registration successful!');
    }

    /**
     * Where to send user after login/register
     */
    protected function redirectToFor(User $user): string
    {
        // Safe access – prevents "on null" error
        $roleSlug = $user->role?->slug ?? 'customer';

        return match (strtolower($roleSlug)) {
            'admin'    => route('admin.dashboard'),
            'waiter'   => route('waiter.dashboard'),       // adjust if you have different route names
            'kitchen'  => route('kitchen.dashboard'),
            'cashier'  => route('cashier.dashboard'),
            'customer' => route('customerOrder.menu.index'),
            default    => route('customerOrder.menu.index'),
        };
    }
}
