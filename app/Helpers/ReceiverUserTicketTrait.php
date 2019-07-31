<?php

namespace App\Helpers;

use App\Helpers\UserRole;

trait ReceiverUserTicketTrait 
{
    public function created_tickets()
    {
        return $this->hasMany('App\Ticket','email','sender_email');
    }

    public function created_ticket_count()
    {
        return $this->created_tickets->count();
    }
}
