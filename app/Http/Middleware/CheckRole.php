<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user has a valid role
        if (!$user->role) {
            Auth::logout();
            return redirect('/login')->with('error', 'Role tidak valid. Silakan login kembali.');
        }

        if ($user->role !== $role) {
            // Jika user mencoba mengakses halaman yang tidak sesuai rolenya
            if ($user->role === 'admin') {
                return redirect('/admin/users')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
            } elseif ($user->role === 'user') {
                return redirect('/user/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
            } else {
                // Invalid role, logout
                Auth::logout();
                return redirect('/login')->with('error', 'Role tidak valid. Silakan login kembali.');
            }
        }

        return $next($request);
    }
}
