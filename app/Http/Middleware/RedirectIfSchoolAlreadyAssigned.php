<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfSchoolAlreadyAssigned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $isLoggedIn = Auth::check();
        if ($isLoggedIn) {
            $user = Auth::user();
            if ($user->school_id !== 0) {
                return redirect()->action([\App\Http\Controllers\SchoolController::class, 'showSchool'], ['school' => $user->school_id]);

//                return redirect()->action([\App\Http\Controllers\SchoolController::class, 'showAddSchoolView'], ['id' => $user->school->id]);
                //more than what I need here.  id part goes for viewing actual roster page.
            }
        }

        return $next($request);
    }
}
