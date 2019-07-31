<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Ticket;

class TicketCaterMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
   
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket=$ticket; 
        $this->subject='Ticket '.$ticket->control_number.' | '.$ticket->title.' catered!';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.catered')->subject($this->subject);
    }
}
