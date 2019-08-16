<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use State;
use Urgency;

class Task extends Model
{
    protected $fillable=[
        'name', 
        'description', 
        'project_id', 
        'created_by', 
        'assigned_to', 
        'start_date', 
        'due_date', 
        'label', 
        'state', 
        'priority',
        'remarks',
    ];
 

    public function project()
    {
        return $this->belongsTo('App\Project','project_id');
    }
    public function assignee()
    {
        return $this->belongsTo('App\User','assigned_to');
    }
    public function attachments()
    {
        return $this->hasMany('App\TaskAttachment','task_id');
    }
    public function creator()
    {
        return $this->belongsTo('App\User','created_by');
    }
    protected $appends=[
        'description_html',
        'remarks_html',
        'state_text',
        'priority_text'
    ];
    public function getStateTextAttribute()
    {
        return State::state($this->state);
    }
    public function getRemarksHtmlAttribute()
    {
        return nl2br($this->remarks);
    }
    public function getDescriptionHtmlAttribute()
    {
        return nl2br($this->description);
    }
    public function getPriorityTextAttribute()
    {
        return Urgency::urgency($this->priority);
    }
}
