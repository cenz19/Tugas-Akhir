<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class AuthController extends Controller
{
    //
    public function showLoginForm()
    {
        return view('auth.login');  // Ensure this corresponds to your login Blade view
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        // Validate user input
        $credentials = $request->validate([
            'email' => ['required', 'email'],  // Using email instead of username
            'password' => ['required', 'string'],
        ]);
    
        // Attempt to log the user in using the email and password
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            // Authentication was successful, redirect to the post page
            return redirect()->intended('/post');
        }
    
         // If authentication fails, flash a message for alert in session
        session()->flash('alert', 'The provided credentials do not match our records.');

        // Redirect back to login page
        return redirect()->route('login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'role' => 'required|in:user,pakar',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect('/login')->with('success', 'Registration successful! Please log in.');
    }
}
