<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use State;
use App\Helpers\TaskHelper;

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
        'start_date'=>'date:Y-m-d',
        'due_date'=>'date:Y-m-d',
    ];


    protected $appends=[
        'state_text',
        'description_html',
        'human_start_date',
        'human_due_date',
        'completion_rate',
    ];

    public function getHumanStartDateAttribute()
    {
        if($this->start_date==null)
            return '';
        else
            return $this->start_date->format("M d, Y");
    }
    public function getHumanDueDateAttribute()
    {
        if($this->due_date==null)
            return '';
        else
            return $this->due_date->format("M d, Y");
    }

    public function getDescriptionHtmlAttribute()
    {
        return \nl2br($this->description);
    }

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
    public function attachments()
    {
        return $this->hasMany('App\ProjectAttachment','project_id');
    }


    public function histories()
    {
        return $this->hasMany('App\ProjectHistory','project_id');
    }

    public function getCompletionRateAttribute()
    {
        return TaskHelper::completion_rate($this);
    }
}
