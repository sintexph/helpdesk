<?php

namespace App\Http\Middleware;

use Closure;
use App\Ticket;
use App\TicketNote;

class TicketActionMiddleware
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
        # Get the authenticated user
        $user=auth()->user();
        # Check if login
        if ($user==null)
            return redirect()->route('login'); 

        $ticket=Ticket::find($request['id']);

        if(!$user->can('update', $ticket))
            abort(401,'You are unauthorized to do any action of the ticket!');

        return $next($request);
    }
}
