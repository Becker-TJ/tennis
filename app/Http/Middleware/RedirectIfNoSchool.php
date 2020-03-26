<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNoSchool
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
        if($isLoggedIn) {
            $user = Auth::user();
            if($user->school == null) {
                return redirect()->action('SchoolController@showAddSchoolView');

//                return redirect()->action('SchoolController@showAddSchoolView', ['id' => $user->school->id]);
                //more than what I need here.  id part goes for viewing actual roster page.
            }
        }
        return $next($request);
    }
}
