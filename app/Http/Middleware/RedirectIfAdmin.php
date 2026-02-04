<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        \Log::info('RedirectIfAdmin Middleware - Checking user: ' . (Auth::check() ? Auth::user()->email : 'Not logged in'));

        if (Auth::check() && Auth::user()->usertype === 'admin') {
        \Log::info('RedirectIfAdmin Middleware - REDIRECTING admin from user route: ' . $request->path());
        return redirect()->route('admin.dashboard');
        }

        \Log::info('RedirectIfAdmin Middleware - ALLOWING non-admin to: ' . $request->path());
        return $next($request);
    }
}
