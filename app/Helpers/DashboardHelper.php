<?php

namespace App\Helpers;

use App\Helpers\StatisticsHelper;

class DashboardHelper 
{
    /**
     * Get the first and the end date of the current week
     */
    public static function first_end_date()
    {
        $first=new \Carbon\Carbon;
        $last=new \Carbon\Carbon;
        $first->modify('this week');
        $last->modify('this week +6 days');

        return (object)[
            'from'=>$first,
            'to'=>$last,
        ];
    }
 
    /**
     * Get the summarize of ratings in a week
     */
    public static function rating_summary()
    {
        $date=static::first_end_date();
        
        return StatisticsHelper::rating_summary(null,null,$date->from->format('Y-m-d'),$date->to->format('Y-m-d'));
    }
}
