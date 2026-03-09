<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    public function sendOtp(Request $request)
    {
        $data = $request->validate([
            'login' => ['required', 'string', 'max:100'],
        ]);

        $login = trim($data['login']);

        $customer = Customer::query()
            ->where('email', $login)
            ->orWhere('phone', $login)
            ->first();

        if (!$customer) {
            return back()->withErrors([
                'login' => 'Customer with this email or phone was not found.',
            ])->withInput();
        }

        $otpCode = (string) random_int(100000, 999999);

        $request->session()->put('frontend_otp', [
            'customer_id' => $customer->id,
            'contact' => $login,
            'code' => $otpCode,
            'expires_at' => now()->addMinutes(5)->timestamp,
        ]);

        Log::info('Frontend customer OTP generated', [
            'customer_id' => $customer->id,
            'contact' => $login,
            'otp' => $otpCode,
        ]);

        return redirect()->route('frontend.verify.form')->with([
            'success' => 'OTP sent successfully. Please verify to continue.',
            'debug_otp' => app()->environment('local') ? $otpCode : null,
        ]);
    }

    public function showVerifyForm(Request $request)
    {
        $otpData = $request->session()->get('frontend_otp');
        if (!$otpData) {
            return redirect()->route('frontend.login')
                ->with('error', 'Please request an OTP first.');
        }

        return view('frontend.auth.verify', [
            'contact' => $otpData['contact'] ?? '',
            'debugOtp' => session('debug_otp'),
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $otpData = $request->session()->get('frontend_otp');
        if (!$otpData) {
            return redirect()->route('frontend.login')
                ->with('error', 'OTP expired. Please request a new one.');
        }

        if (now()->timestamp > ((int) ($otpData['expires_at'] ?? 0))) {
            $request->session()->forget('frontend_otp');
            return redirect()->route('frontend.login')
                ->with('error', 'OTP expired. Please request a new one.');
        }

        if ((string) $otpData['code'] !== (string) $data['otp']) {
            return back()->withErrors(['otp' => 'Invalid OTP code.'])->withInput();
        }

        $request->session()->put('frontend_customer_id', $otpData['customer_id']);
        $request->session()->forget('frontend_otp');

        return redirect()->route('frontend.dashboard')->with('success', 'Login successful.');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('frontend_customer_id');
        $request->session()->forget('frontend_otp');

        return redirect()->route('frontend.login')->with('success', 'Logged out successfully.');
    }
}

