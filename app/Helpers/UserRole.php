<?php

namespace App\Helpers; 

class UserRole 
{
    const SENDER=1;
    const SUPPORT=2;
    const MODERATOR=3;
    const ADMINISTRATOR=4; 


    public static function role($value)
    {
        $role='';

        switch($value)
        {
            case static::SENDER:
                $role='Sender';
            break;
            case static::SUPPORT:
                $role='Support';
            break;
            case static::MODERATOR:
                $role='Moderator';
            break;
            case static::ADMINISTRATOR:
                $role='Administrator';
            break;
            
        }
        return $role;
    }
    
    /**
     * Get the role number based on role text string
     * @param $value The role text
     */
    public static function role_number($value)
    { 
        $role='static::'.\str_replace(" ","_",strtoupper($value));
        
        if(defined($role))
        {
            return constant($role);
        }
        else
            return null;
    }

 
}
