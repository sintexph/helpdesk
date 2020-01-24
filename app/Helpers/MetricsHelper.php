<?php

namespace App\Helpers;

use App\Ticket;
use App\Helpers\State;

class MetricsHelper 
{
    
    public static function update_tickets()
    {
        # Get the tickets that was solve
        $tickets=Ticket::where('state',State::CLOSED)->get();

        foreach ($tickets as $ticket) {
            Metrics::set_metrics($ticket);
        }

        return $tickets;
    }
}
