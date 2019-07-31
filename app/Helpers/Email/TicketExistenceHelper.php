<?php

namespace App\Helpers\Email;

class TicketExistenceHelper 
{
    /**
     * Check if the email is a valid ticket conversation
     */
    public static function check_valid_ticket_conversation($subject)
    {
        $validators=['RE: Ticket'];
        foreach ($validators as $value) {
            if (strpos($subject, $value) === false) {
                return false;
            }
        }

        return true;
    }

    public static function extract_control_number($subject)
    {
        $exploded=explode(" ",$subject);
        return $exploded[2];
    }
}
