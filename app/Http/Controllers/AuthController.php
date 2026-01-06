<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Check role dan redirect
            if (Auth::check() && Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Show register form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Handle register
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Account created successfully!');
    }

    // Google OAuth redirect
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Google OAuth callback
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $email = $googleUser->getEmail();
            $name = $googleUser->getName();
            $googleId = $googleUser->getId();
            $avatar = $googleUser->getAvatar();

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'google_id' => $googleId,
                    'avatar' => $avatar,
                    'role' => 'customer',
                ]
            );

            Auth::login($user);

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Failed to login with Google: ' . $e->getMessage());
        }
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logged out successfully!');
    }
}