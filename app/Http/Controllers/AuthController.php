<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register_form(Request $request){
        return view('register_form');
    }

    public function login_form(Request $request)
    {
        return view('login_form');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(
        Auth::user()->is_admin
            ? route('admin.dashboard')
            : route('dashboard')
    );
}
}
