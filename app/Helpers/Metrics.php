<?php

namespace App\Helpers;

use App\Setting;
use App\Ticket;

class Metrics 
{
    private $catered;
    private $processing;
    private $solved;
    private $closed;

    private static $instance;
    
    public static function get()
    {
        if(is_null( self::$instance ))
            self::$instance = new self();
        
        return self::$instance;
    }

    private function __construct()
    {
        $settings=Setting::all();

        $this->catered=$settings->where('name','=','TARGET_CATERED')->first();
        $this->processing=$settings->where('name','=','TARGET_PROCESSING')->first();
        $this->solved=$settings->where('name','=','TARGET_SOLVED')->first();
        $this->closed=$settings->where('name','=','TARGET_CLOSED')->first();
    }
    
    public function catered_metrics()
    {
        return (double)$this->catered->value;
    }

    public function processing_metrics()
    {
        return (double)$this->processing->value;
    }

    public function solved_metrics()
    {
        return (double)$this->solved->value;
    }
    public function closed_metrics()
    {
        return (double)$this->closed->value;
    }
  
    public static function set_metrics(Ticket $ticket)
    {
        foreach ($ticket->targets as $target) {
            $date=new \Carbon\Carbon($ticket->created_at);
            $target->due_date=static::calculate($date,$target->metrics);
            $target->save();
        }
    }
    static function calculate(\Datetime $date_1,$metrics)
    {
        # Default to 8 hours
        $shift_hour=8;

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
    static function shift_hour($shift_start,$shift_end)
    {
        $now=\Carbon\Carbon::now();
        if($shift_start>$shift_end)
            return static::cal_hour_diff($now->format("Y-m-d ".$shift_start.":00:00"),$now->addDays(1)->format("Y-m-d ".$shift_end.":00:00"));
        else
            return static::cal_hour_diff($now->format("Y-m-d ".$shift_start.":00:00"),$now->format("Y-m-d ".$shift_end.":00:00")); 
    }

    /**
     * Calculate the hour difference between two dates
     * @param $first_date The first date
     * @param $second_date The second date
     * @return The difference of two dates
     */
    public static function cal_hour_diff($first_date,$second_date)
    {
        
        if($first_date==null || $second_date==null)
                return null;
        return round((strtotime($second_date)-strtotime($first_date))/3600, 5);
    }
 
}
