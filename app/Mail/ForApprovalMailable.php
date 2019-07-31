<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForApprovalMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ticket)
    {
        $this->ticket=$ticket;
        $this->url=route('approval',$ticket->id);
        $this->subject='Ticket '.$ticket->control_number.' | '.$ticket->title.' for approval!';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.approval')
                    ->subject($this->subject);
    }
}
