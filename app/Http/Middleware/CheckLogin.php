<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Session::has('user') || Session::get('user')->role !== $role) {
            if ($_SERVER['REQUEST_URI'] == '/admin') {
                return redirect('/admin/login');
            }
            if (preg_match('/delivery/', $_SERVER['REQUEST_URI'])) {
                return redirect('/delivery/login');
            }
            abort(403, 'Unauthorized');
            // return redirect('/admin/login');
        }

        return $next($request);
    }
}
