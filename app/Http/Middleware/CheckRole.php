<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role1, $role2 = '')
    {
        $user = Auth::user();
        if ($user->role->name == $role1 || $user->role->name == $role2)
            return $next($request);
        return redirect('/dashboard')->with('warning', 'Bạn không có quyền truy cập vào chức năng này');
    }
}
