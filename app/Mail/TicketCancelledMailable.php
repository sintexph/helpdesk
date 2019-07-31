<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Ticket;

class TicketCancelledMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $receiver_name;
   
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket,$receiver_name)
    {
        $this->ticket=$ticket; 
        $this->subject='Ticket '.$ticket->control_number.' | '.$ticket->title.' cancelled!';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.ticket-cancelled')->subject($this->subject);
    }
}
