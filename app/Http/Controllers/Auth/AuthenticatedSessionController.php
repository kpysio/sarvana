<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Providers\RouteServiceProvider;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => __('auth.failed'),
            ])->onlyInput('email');
        }

        $user = Auth::user();
        // Only allow provider login if is_verified is true (1)
        if ($user->user_type === 'provider' && !$user->is_verified) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Your provider account is not verified. Please wait for admin approval.'
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        // Role-based redirect
        if ($user->user_type === 'provider') {
            return redirect()->intended('/provider/dashboard');
        } elseif ($user->user_type === 'customer') {
            return redirect()->intended('/customer/dashboard');
        } elseif ($user->user_type === 'admin') {
            return redirect()->intended('/admin/dashboard');
        } else {
            return redirect()->intended('/dashboard');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
