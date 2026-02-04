<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = auth()->user();

        // Add this debug output to see what's happening
        \Log::info('=== LOGIN PROCESS ===');
        \Log::info('User: ' . $user->email);
        \Log::info('Usertype: ' . $user->usertype);
        \Log::info('Is admin: ' . ($user->usertype === 'admin' ? 'YES' : 'NO'));

        if ($user->usertype === 'admin') {
            \Log::info('Redirecting to ADMIN dashboard');
            return redirect('/admin/dashboard');
        }

        \Log::info('Redirecting to USER dashboard');
        return redirect('/dashboard');
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
