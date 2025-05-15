<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
   /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login dan role-nya adalah admin
        if (auth()->check() && auth()->user()->isAdmin()) {
            return $next($request);
        }

        // Jika bukan admin, redirect ke home dengan pesan error
        return redirect('/')->with('error', 'You do not have access to this page.');
    }
}


