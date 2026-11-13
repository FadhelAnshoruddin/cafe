<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Memeriksa hak akses pengguna berdasarkan role.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Memastikan pengguna sudah login
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Memeriksa role pengguna
        if (!in_array($request->user()->role, $roles)) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}