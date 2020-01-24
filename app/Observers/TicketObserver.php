<?php

namespace App\Observers;

use App\Ticket;
use App\TicketTarget;
use State;
use App\Helpers\Metrics;

class TicketObserver
{
    /**
     * Handle the ticket "created" event.
     *
     * @param  \App\Ticket  $ticket
     * @return void
     */
    public function created(Ticket $ticket)
    {
        $metrics=Metrics::get();

        TicketTarget::create([
            'ticket_id'=>$ticket->id,
            'target'=>State::CATERED,
            'due_date'=>\Carbon\Carbon::now(),
            'metrics'=>$metrics->catered_metrics(),
        ]);

        TicketTarget::create([
            'ticket_id'=>$ticket->id,
            'target'=>State::PROCESSING,
            'due_date'=>\Carbon\Carbon::now(),
            'metrics'=>$metrics->processing_metrics(),
        ]);

        TicketTarget::create([
            'ticket_id'=>$ticket->id,
            'target'=>State::SOLVED,
            'due_date'=>\Carbon\Carbon::now(),
            'metrics'=>$metrics->solved_metrics(),
        ]);

        TicketTarget::create([
            'ticket_id'=>$ticket->id,
            'target'=>State::CLOSED,
            'due_date'=>\Carbon\Carbon::now(),
            'metrics'=>$metrics->closed_metrics(),
        ]);

        # set the metrics
        Metrics::set_metrics($ticket);
    }

    /**
     * Handle the ticket "updated" event.
     *
     * @param  \App\Ticket  $ticket
     * @return void
     */
    public function updated(Ticket $ticket)
    {
        //
    }

    /**
     * Handle the ticket "deleted" event.
     *
     * @param  \App\Ticket  $ticket
     * @return void
     */
    public function deleted(Ticket $ticket)
    {
        //
    }

    /**
     * Handle the ticket "restored" event.
     *
     * @param  \App\Ticket  $ticket
     * @return void
     */
    public function restored(Ticket $ticket)
    {
        //
    }

    /**
     * Handle the ticket "force deleted" event.
     *
     * @param  \App\Ticket  $ticket
     * @return void
     */
    public function forceDeleted(Ticket $ticket)
    {
        //
    }
}
