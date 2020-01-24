<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\State;

class TicketTarget extends Model
{
    protected $fillable=[
        'ticket_id',
        'target',
        'completed_date',
        'due_date',
        'assigned_to',
        'metrics',
    ];

    protected $appends=[
        'target_text',
        'result',
        'hit_target',
    ];
 

    public function getResultAttribute()
    {
        if($this->completed_date==null)
            return null;

        $completed_date=new \Carbon\Carbon($this->completed_date);
        $due_date=new \Carbon\Carbon($this->due_date);
        
        return $due_date->gt($completed_date);
    }

    public function assignee()
    {
        return $this->belongsTo('App\User','assigned_to');
    }
    
    public function getTargetTextAttribute()
    {
        return State::state($this->target);
    }

    public function getHitTargetAttribute()
    {
        if($this->result===true)
            return 'PASSED';
        elseif($this->result===false)
            return 'FAILED';
        else
            return '';
    }
    
}
