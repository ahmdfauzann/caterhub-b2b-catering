<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MerchantProfile;
use App\Models\CustomerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            return $this->redirectBasedOnRole($user)
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register-choice');
    }

    public function showRegisterCustomerForm()
    {
        return view('auth.register-customer');
    }

    public function registerCustomer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'office_address' => 'required|string',
            'city' => 'required|string|max:100',
            'employee_count' => 'nullable|integer|min:1',
        ], [
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau login.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'phone' => $request->phone,
        ]);

        CustomerProfile::create([
            'user_id' => $user->id,
            'company_name' => $request->company_name,
            'pic_name' => $request->name,
            'phone' => $request->phone,
            'office_address' => $request->office_address,
            'city' => $request->city,
            'employee_count' => $request->employee_count,
        ]);

        Auth::login($user);

        return redirect()->route('customer.dashboard')
            ->with('success', 'Pendaftaran akun kantor berhasil! Selamat datang di CaterHub.');
    }

    public function showRegisterMerchantForm()
    {
        return view('auth.register-merchant');
    }

    public function registerMerchant(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'cuisine_type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ], [
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'merchant',
            'phone' => $request->phone,
        ]);

        $slug = Str::slug($request->company_name) . '-' . Str::random(4);

        MerchantProfile::create([
            'user_id' => $user->id,
            'company_name' => $request->company_name,
            'slug' => $slug,
            'description' => $request->description ?? 'Mitra Katering Terpercaya.',
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'cuisine_type' => $request->cuisine_type,
            'status' => 'active',
        ]);

        Auth::login($user);

        return redirect()->route('merchant.dashboard')
            ->with('success', 'Pendaftaran katering berhasil! Selamat datang di Portal Merchant.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar dari akun.');
    }

    private function redirectBasedOnRole($user)
    {
        if ($user->isMerchant()) {
            return redirect()->route('merchant.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    }
}
