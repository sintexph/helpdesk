<?php

namespace App;

use App\Ticket;
use App\TicketTarget;
use State;
use App\Helpers\Metrics;

class Test
{
    public static function cal(\Datetime $date_1,$shift_start,$shift_end,$break_time=null,$metrics=0.25)
    {
        if($break_time==null)
            $break_time=0;

        $shift_hour=static::shift_hour($shift_start,$shift_end);

        if($metrics>=24)
        {
            $days=$metrics/$shift_hour;
            $hour=($days-floor($days))*24;
            $minutes=($hour-floor($hour))*60;

            if($days>=1)
                $date_1=$date_1->addDays($days);
            if($hour>=1)
                $date_1=$date_1->addHours($hour);
            if($minutes>=1)
                $date_1=$date_1->addMinutes($minutes);

            

            return $date_1;
        }
        elseif($metrics>=1)
        {
            return $date_1->addHour($metrics);
        }
        else
        {
            $minutes=$metrics*60;
            return $date_1->addMinutes($minutes);
        }

    }
 


    static function save_targets()
    {
        foreach (Ticket::all() as $ticket) {
            static::set_targets($ticket);
        }
    }
    static function set_targets(Ticket $ticket)
    {
        $metrics=Metrics::get();
        foreach ($ticket->targets as $target) {
            $target->due_date=$metrics->catered_metrics();
            $target->save();
        }
    }
  
    
}
