<?php

namespace App\Helpers;

use App\Helpers\UserRole;

trait UserRoleTrait 
{
    
    public function __construct()
    {
        $this->appends[]='role_text';
    }
    /**
     * Accepts Role String and Role Classes
     */
    public function roleIs($value)
    {
        $role=UserRole::role_number($value); 
        
        if($role==$this->role)
            return true;
        elseif($role==null)
        {
            if($this->role==$value)
                return true;
            else
                return false;
        }
        
    }
    
    
    public function getRoleTextAttribute()
    {
        return UserRole::role($this->role);
    }
}
