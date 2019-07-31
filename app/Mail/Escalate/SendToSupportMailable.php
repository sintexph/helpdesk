<?php

namespace App\Mail\Escalate;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Ticket;

class SendToSupportMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $escalated_by;
    public $escalated_to;
   
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket,$escalated_by,$escalated_to)
    {
        $this->ticket=$ticket;
        $this->escalated_by=$escalated_by;
        $this->escalated_to=$escalated_to;
        $this->subject='Ticket '.$ticket->control_number.' | '.$ticket->title.' escalated!';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.escalate.support')->subject($this->subject);
    }
}
