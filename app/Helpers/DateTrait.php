<?php

namespace App\Helpers;

trait DateTrait 
{
    public function getCreatedAtAttribute($val)
    {
        $date=new \Carbon\Carbon($val);
        $date=$date->format('M d, Y H:m:s A');
        return $date;
    }
}
