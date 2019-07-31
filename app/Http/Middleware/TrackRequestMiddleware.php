<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\TrackRequestHelper;

class TrackRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $session_track_email=TrackRequestHelper::get_auth_email();
        
        if(empty($session_track_email))
            return redirect()->route('track.show_login');

        return $next($request);
    }
}
