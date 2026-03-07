<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use function Pest\Laravel\swap;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

        public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            switch ($user->role) {
                case 'admin':
                    return redirect()->route('dashboard');

                case 'waiter':
                    return redirect()->route('orders.create');

                case 'kitchen':
                    return redirect()->route('kitchen');

                case 'cashier':
                    return redirect()->route('checkout');

                default:
                    Auth::logout();
                    return redirect('/login');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.'
        ])->onlyInput('email');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Logged out successfully');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['nullable', 'in:waiter,kitchen,cashier,admin'],
        ]);

        $role = $data['role'] ?? ''; // Default interface role selcet role 

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $role,
        ]);

        Auth::login($user);
                    
        switch ($user->role) {
            case 'admin':
                return redirect()->route('dashboard');

            case 'waiter':
                return redirect()->route('orders.create');

            case 'kitchen':
                return redirect()->route('kitchen');

            case 'cashier':
                return redirect()->route('checkout');

            default:
                Auth::logout();
                return redirect('/login');
        }
    }
    
}
