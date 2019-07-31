<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifySenderMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $receiver;
    public $ticket_number;
    public $ticket_token;
    public $sender_name;
    public $sender_email;
    public $title;
    public $content;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket)
    {
        $this->receiver=$ticket->sender_name;
        $this->control_number=$ticket->control_number;
        $this->ticket_token=$ticket->token;
        $this->sender_name=$ticket->sender_name;
        $this->sender_email=$ticket->sender_name;
        $this->title=$ticket->title;
        $this->content=$ticket->content;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.notify_sender');
    }
}
