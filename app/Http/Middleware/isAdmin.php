<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId     = auth()->user()->id;
        $userName   = auth()->user()->name;
        $userAdmin  = User::select('id')->where('users.name', $userName)->first();

        if ($userName && $userId === $userAdmin->id) {
        return $next($request);
    }

    return response()->json([
        'message' => 'Anda tidak bisa mengakses halaman admin'
    ], 401);
    }
}
