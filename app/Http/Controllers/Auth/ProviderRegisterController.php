<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProviderRegisterController extends Controller
{
    public function show()
    {
        return view('auth.register-provider');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 'provider',
            'status' => 'pending',
        ]);

        // Optionally notify admin for approval here

        return redirect()->route('login')->with('status', 'Registration submitted! Wait for admin approval.');
    }
} 