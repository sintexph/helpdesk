<?php

namespace App\Helpers;

use App\Setting;

class Target 
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
    
    public function catered_target()
    {
        return (double)$this->catered->value;
    }

    public function processing_target()
    {
        return (double)$this->processing->value;
    }

    public function solved_target()
    {
        return (double)$this->solved->value;
    }
    public function closed_target()
    {
        return (double)$this->closed->value;
    }

    /**
     * Check if pass or fail the processing target
     * @return 1 = PASS, 0 = FAIL
     */
    public function pf_processing($date_from,$date_to)
    { 
        if($date_from==null || $date_to==null)
            return null;
        else
        {
            if($this->cal_hour_diff($date_from,$date_to)<$this->processing_target())
                return 1;
            else
                return 0;
        }
    }

    /**
     * Check if pass or fail the catered target
     * @return 1 = PASS, 0 = FAIL
     */
    public function pf_catered($date_from,$date_to)
    { 
        if($date_from==null || $date_to==null)
            return null;
        else
        {
            if($this->cal_hour_diff($date_from,$date_to)<$this->catered_target())
                return 1;
            else
                return 0;
        }
    }

    /**
     * Check if pass or fail the solved target
     * @return 1 = PASS, 0 = FAIL
     */
    public function pf_solved($date_from,$date_to)
    { 
        if($date_from==null || $date_to==null)
            return null;
        else
        {
            if($this->cal_hour_diff($date_from,$date_to)<$this->solved_target())
                return 1;
            else
                return 0;
        }
    }

    /**
     * Check if pass or fail the closed target
     * @return 1 = PASS, 0 = FAIL
     */
    public function pf_closed($date_from,$date_to)
    { 
        if($date_from==null || $date_to==null)
            return null;
        else
        {
            if($this->cal_hour_diff($date_from,$date_to)<$this->closed_target())
                return 1;
            else
                return 0;
        }
    }
    /**
     * Calculate the hour difference between two dates
     * @param $first_date The first date
     * @param $second_date The second date
     * @return The difference of two dates
     */
    private function cal_hour_diff($first_date,$second_date)
    {
        
        if($first_date==null || $second_date==null)
                return null;
        return round((strtotime($second_date)-strtotime($first_date))/3600, 5);
    }
}
