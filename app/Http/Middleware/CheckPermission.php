<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permissions): Response
    {
        $permissionsArray = explode('|', $permissions);

        foreach ($permissionsArray as $permission) {
            if ($request->user() && $request->user()->hasPermissionTo($permission)) {
                return $next($request);
            }
        }

        return redirect()->route('home');
    }
}
