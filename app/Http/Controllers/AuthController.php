<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginShow() {
        return view('Auth.login');
    }

    public function login(LoginRequest $request) {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) { //$request->boolean('remember')
            $request->session()->regenerate();
            return redirect()->intended(route('welcome'));
        }
        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    public function registerShow() {
        return view('Auth.register');
    }

    public function register(RegisterRequest $request) {
        $user = $request->validated();
        $user['password'] = Hash::make($user['password']);
        User::create($user);
        return redirect()->route('login.show')->with('success', 'You are Registered successfully');
    }

    public function logout(Request $request) {
        session()->flush();
        Auth::logout();
        return redirect()->route('login.show')->with('success', 'You are Logged out successfully');
    }

}
