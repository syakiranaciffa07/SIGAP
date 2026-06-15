<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $user = $request->user();

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.'),
            'petugas' => redirect()->route('petugas.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.'),
            'pimpinan' => redirect()->route('pimpinan.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.'),
            default => redirect()->route('masyarakat.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.'),
        };
    }
}
