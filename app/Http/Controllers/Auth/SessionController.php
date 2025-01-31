<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function login()
    {
        return view('session.login');
    }

    public function verify()
    {
        $attributes = request()->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required'
        ]);


        if (!Auth::attempt($attributes)) {
            throw ValidationException::withMessages([
                'email' => 'Your provided credentials could not be verified'
            ]);
        }

        session()->regenerate();

        return redirect('/')->with('Success', 'Welcome Back');
    }

    public function register(Request $request)
    {
        $attributes = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => Hash::make($attributes['password']),
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Your account has been created.');
    }

    public function destroy()
    {
        Auth::logout();

        return redirect('/')->with('Success', 'Goodbye!');
    }
}
