<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Ticket;
use App\User;

class NewTicketMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $receiver;
    public $sender_factory;
    public $sender_name;
    public $sender_email;
    public $ticket_id;
    public $content;
    public $title; 

    public $ticket;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket,User $user)
    {
        $this->ticket=$ticket;
        $this->receiver=$user->name;
        $this->subject='New ticket under '.$ticket->sender_factory.' - #'.$ticket->control_number;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.new-request');
    }
}
