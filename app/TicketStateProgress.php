<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use State;

class TicketStateProgress extends Model
{
    protected $fillable=['content','state','ticket_id'];

    protected $appends=[
        'time_ago',
    ];
    
    public function getStateAttribute($value)
    {
        return State::state($value);
    }
    
    public function getContentAttribute($value)
    {
        return ucfirst(strtolower($value));
    }
    
    public function getTimeAgoAttribute()
    {
        $date=new \Carbon\Carbon($this->created_at);
        return $date->diffForHumans(); 
    }

}
