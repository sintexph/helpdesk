<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\TicketConversation;

class ReplyConversationMailable extends Mailable
{
    use Queueable, SerializesModels; 

    public $ticket;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TicketConversation $ticket_conversation)
    {
        $this->ticket=$ticket_conversation->ticket;
        $this->subject="Message from - ".$ticket_conversation->creator->name." - Ticket: ".$this->ticket->control_number; 
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.reply-conversation');
    }
}
