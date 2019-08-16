<?php

namespace App\Helpers;

class Urgency 
{
    const LOW=1;
    const NORMAL=2;
    const HIGH=3;

    public static function urgency($value)
    {
        $urgency='Low';

        switch($value)
        {
            case static::LOW:
                $urgency='Low';
            break;
            case static::NORMAL:
                $urgency='Normal';
            break;
            case static::HIGH:
                $urgency='High';
            break;
        }

        return $urgency;
    }
}
