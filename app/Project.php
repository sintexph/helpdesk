<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use State;

class Project extends Model
{
    protected $fillable=[
        'name', 
        'description', 
        'requested_by', 
        'created_by', 
        'start_date', 
        'due_date', 
        'followers', 
        'tags', 
        'state',
        'is_public',
    ];

    protected $casts=[
        'followers'=>'array',
        'tags'=>'array',
        'is_public'=>'boolean',
    ];


    protected $appends=[
        'state_text',
    ];

    public function getStateTextAttribute()
    {
        return State::state($this->state);
    }

    public function requestor()
    {
        return $this->belongsTo('App\User','requested_by');
    }
    public function creator()
    {
        return $this->belongsTo('App\User','created_by');
    }


    public function project_followers()
    {
        return \App\User::whereIn('id',$this->followers)->get();
    }

    public function tasks()
    {
        return $this->hasMany('App\Task','project_id');
    }
}
