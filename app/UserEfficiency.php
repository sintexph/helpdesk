<?php

namespace App;
use DB;

class UserEfficiency extends User
{
    public $date_from;
    public $date_to;

    public function __construct()
    { 
        $this->table='user_efficiencies';
        $this->appends=['rate'];
        $this->fillable=[];
    }
    
    

    public function getRateAttribute()
    {

        $total_tickets=$this->tickets->count();

        dump([$this->id,$total_tickets]);

        $hit_catered=($this->hit_catered/$total_tickets)*100;
        $hit_processing=($this->hit_processing/$total_tickets)*100;
        $hit_solved=($this->hit_solved/$total_tickets)*100;

        $failed_catered=($this->failed_catered/$total_tickets)*100;
        $failed_processing=($this->failed_processing/$total_tickets)*100;
        $failed_solved=($this->failed_solved/$total_tickets)*100;

        return round(($hit_catered+$hit_processing+$hit_solved)/3,0);
    }
}
