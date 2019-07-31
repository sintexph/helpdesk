<?php

namespace App\Helpers;

use App\Helpers\Target;
use App\Ticket;
use App\Helpers\TicketState;

class TargetHelper 
{
    /**
     * UPDATE THE TICKET TARGETS PASSED OF FAILED
     */
    public static function update_tickets()
    {
        # Get the tickets that was solve and the targets was not updated
        $tickets=Ticket::where(function($ticket){
            $ticket->orWhereNull('ht_catered')
                    ->orWhereNull('ht_processing')
                    ->orWhereNull('ht_solved')
                    ->orWhereNull('ht_closed');
        })->orWhere('state',TicketState::SOLVED)->get();

        foreach ($tickets as $ticket) {

            # Calculate passing status based from creation date to catered date
            $ticket->ht_catered=Target::get()->pf_catered($ticket->created_at,$ticket->catered_at);

            # Calculate passing status based from catered date to processing date
            $ticket->ht_processing=Target::get()->pf_processing($ticket->catered_at,$ticket->processing_at);

            # Calculate passing status based from processing date to solved date
            $ticket->ht_solved=Target::get()->pf_solved($ticket->processing_at,$ticket->solved_at);

            # Calculate passing status based from solved date to closed date
            $ticket->ht_closed=Target::get()->pf_closed($ticket->solved_at,$ticket->closed_at);

            $ticket->save();
        }

        return $tickets;
    }
}
