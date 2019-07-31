<?php

namespace App\Http\Middleware;

use Closure;

class SenderTicketMiddleware
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
        $user=auth()->user();
        $ticket=\App\Ticket::find($request['id']);
        if($ticket==null)
            abort(404,'Ticket could not be found');
        
        if($ticket->sender_email!=$user->email)
            abort(401,'You are unauthorized to access the ticket!');

        return $next($request);
    }
}
