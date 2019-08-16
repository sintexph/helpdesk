<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Ticket;
use App\User;

class SendEmailMailable extends Mailable
{
    use Queueable, SerializesModels; 

    public $ticket;
    public $content;
    public $user;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket,User $user,$content,$subject)
    {
        $this->ticket=$ticket;
        $this->content=$content;
        $this->subject=$subject;
        $this->user=$user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.send_email');
    }
}
