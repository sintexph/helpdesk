<?php

namespace App\Helpers;

class State
{
    const PENDING=1;
    const CATERED=2;
    const PROCESSING=3;
    const SOLVED=4;
    const CLOSED=5;
    const HOLD=6;
    const CANCELLED=7;
    const ESCALATED=8;
    const APPLIED_APPROVAL=9;
    const APPROVAL_CANCELLED=10;
    const APPROVED=11;
    const REJECTED=12;
     
    const FOR_CANVASSING=13;
    const CREATED_PURCHASE_REQUISITION=14;
    const PROCESSING_PURCHASE_ORDER=15;
    const DELIVERED=16;
    const READY_FOR_RELEASE=17;

    const UN_HOLD=18;

    const COMPLETED=19;
    const RESEND_APPROVAL=20;

    public static function state($state_value) {
        
        $stateClass = new \ReflectionClass ( 'State' );
        $constants = $stateClass->getConstants();

        $constName = null;
        foreach ( $constants as $name => $value )
        {
            if ( $value == $state_value )
            {
                $constName = $name;
                break;
            }
        }

        $constName=strtolower($constName);
        $constName=\str_replace("_"," ",$constName);

        return ucwords($constName);
    }
}
