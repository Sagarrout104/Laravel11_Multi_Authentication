<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            if (Auth::user()->role != 'customer') {
                Auth::logout();
                return redirect()->route('account.login')->with('error', 'You are not autherized to access this page');
            }
            return redirect()->route('account.dashboard')->with('success', 'You have Login successfully');
        } else {
            return redirect()->route('account.login')->with('error', 'Email and passord is incorrect');
        }
    }

    public function register()
    {
        return view('register');
    }

    public function processRegister(Request $request)
    {

        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed'],

        ]);

        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "role" => 'customer',
        ]);

        return redirect()->route('account.login')->with('success', 'You have registered successfully');
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('account.login')->with('success', 'You have logout successfully');
    }
}
