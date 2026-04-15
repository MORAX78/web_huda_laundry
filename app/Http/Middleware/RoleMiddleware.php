<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $userLevel = auth()->user()->level->level_name;

        if (!in_array($userLevel, $roles)) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}