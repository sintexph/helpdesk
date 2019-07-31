<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\TicketNote;

class AddNoteMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $ticket_note;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TicketNote $ticket_note)
    {
        $this->ticket_note=$ticket_note;
        $this->ticket=$ticket_note->ticket;
        $this->subject=$ticket_note->created_by.' has added a note to Ticket #: '.$this->ticket->control_number;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.note_added');
    }
}
