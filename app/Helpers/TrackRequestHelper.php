<?php

namespace App\Helpers;

use App\Ticket;

class TrackRequestHelper 
{
    public static function get_auth_email()
    {
        return session('tracking_email');
    }

    public static function set_auth_email($email)
    {
        session(['tracking_email' => $email]);
        session(['tracking_auth' => true]);
        session(['tracking_count' => static::count()]);

    }
    public static function destroy_auth_email()
    {
        session()->forget('tracking_email');
        session(['tracking_auth' => false]);
        //session()->flush();
    }
    /**
     * Get the tickets under the authenticated email
     * @return Return an eloquent query only
     */
    public static function get_tickets()
    {
        $tickets=Ticket::where('sender_email',static::get_auth_email());
        return $tickets;
    }
    /**
     * Get the tickets under the authenticated email
     * @return Return an eloquent query only
     */
    public static function count()
    {
        return static::get_tickets()->count();
    }

}
