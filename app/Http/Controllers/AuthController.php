<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $isFirstUser = User::count() === 0;

        $user = User::create([
            'name'           => $data['name'],
            'email'          => $data['email'],
            'password'       => Hash::make($data['password']),
            'webhook_token'  => Str::random(32),
            'plan'           => 'free',
            'trial_ends_at'  => now()->addDays(30),
            'letters_quota'  => 100,
            'min_score'      => 7,
            'is_approved'    => $isFirstUser,
            'is_admin'       => $isFirstUser,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        if (! $user->is_approved) {
            return redirect()->route('pending');
        }

        return redirect()->route('settings')
            ->with('status', 'Welcome! Your 30-day free trial has started. Complete your profile below.');
    }

    public function showPending()
    {
        if (auth()->check() && auth()->user()->is_approved) {
            return redirect()->route('dashboard');
        }

        return view('auth.pending');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, true)) {
            throw ValidationException::withMessages(['email' => 'These credentials do not match our records.']);
        }

        $request->session()->regenerate();

        if (! Auth::user()->is_approved) {
            return redirect()->route('pending');
        }

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('ok', 'You have been logged out successfully.');
    }
}
