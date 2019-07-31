<?php

namespace App\Observers;

use App\Ticket;
use App\Helpers\TicketHelper;

class TicketObserver
{
    /**
     * Handle the ticket "updated" event.
     *
     * @param  \App\Ticket  $ticket
     * @return void
     */
    public function updating(Ticket $ticket)
    {
        $ticket->state=TicketHelper::ticket_number_state($ticket);
    }
}
