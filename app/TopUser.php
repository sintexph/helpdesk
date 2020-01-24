<?php

namespace App;

class TopUser extends User
{
    public function __construct()
    {
        $this->table='users';
    }
    


    public function count_ratings()
    {
        return $this->hasOne('App\Ticket','catered_by','id')->where('user_rating',5);
    }
    public function hit_target_cater()
    {
        return $this->hasMany('App\Ticket','catered_by','id')->where('ht_catered',true);
    }
    public function hit_target_processing()
    {
        return $this->hasMany('App\Ticket','catered_by','id')->where('ht_processing',true);
    }
    public function hit_target_solve()
    {
        return $this->hasMany('App\Ticket','catered_by','id')->where('ht_solved',true);
    }
}
