<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {
        if (Auth::check()) {
            if (Auth::user()->email_verified_at != null) {
                if (Auth::user()->role_id == $roles) {
                    return $next($request);
                } elseif (Auth::user()->role_id == $roles) {
                    return $next($request);
                } else {
                    return response()->json(['error' => 'У вас нет доступа к этой странице !'], 403);
                }
            } else {
                return response()->json(['error' => 'Электронная почта не подтверждена!'], 403);
            }
        } else {
            return response()->json(['error' => 'Не допускается. Вам нужно зарегистрироваться'], 403);
        }
    }
}
