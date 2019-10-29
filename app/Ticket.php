<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\TicketHelper;
use App\Helpers\Urgency;
use State;

class Ticket extends Model
{
    protected $fillable=[

        'control_number', 
        'token',

        'sender_name', 
        'sender_email', 
        'sender_carbon_copies', 
        'sender_internet_protocol_address', 
        'sender_factory',
        'sender_phone', 

        'title', 
        'content',
        'urgency', 

        'catered_by', 
        'catered_at', 
        'processing_at', 

        'category',
        
        'solution', 
        'solved_at', 
        'closed_at', 
        'hold_at', 
        'un_hold_at',
        
        'user_rating', 
        'user_feedback',

        'escalated',

        'cancelled_at',
        'cancelled_by',
        'cancellation_reason',

        'approved_at',
        'approver_email',
        'approver_name',

        'state',

        'ht_catered',
        'ht_processing',
        'ht_solved',
        'ht_closed',
    ];

    protected $casts=[
        'sender_carbon_copies'=>'array',
        'urgency'=>'integer',
        'state'=>'integer',
        'user_rating'=>'integer',
        'escalated'=>'boolean',

        'ht_catered'=>'integer',
        'ht_processing'=>'integer',
        'ht_solved'=>'integer',
        'ht_closed'=>'integer',
    ];

    protected $appends=[
        'state_text',
        'content_preview',
        'plain_text_content',
        'urgency_text',
        'time_ago',
        'created_date'
    ];
    
    public function sender()
    {
        return $this->belongsTo('App\User','sender_email','email');
    }

    public function getCreatedDateAttribute()
    {
        $date=new \Carbon\Carbon($this->created_at);
        return $date->format('M d, Y'); 
    }

    public function getTimeAgoAttribute()
    {
        $date=new \Carbon\Carbon($this->created_at);
        return $date->diffForHumans(); 
    }

    public function caterer()
    {
        return $this->belongsTo('App\User','catered_by');
    }
    
    public function references()
    {
        return $this->hasMany('App\TicketReference','ticket_id');
    }

    public function attachments()
    {
        return $this->hasMany('App\TicketAttachment','ticket_id');
    }

    public function conversations()
    {
        return $this->hasMany('App\TicketConversation','ticket_id');
    }
    public function notes()
    {
        return $this->hasMany('App\TicketNote','ticket_id');
    }

    public function getUrgencyTextAttribute()
    {
        return Urgency::urgency($this->urgency);
    }


    public function state_progress()
    {
        return $this->hasMany('App\TicketStateProgress','ticket_id');
    }
    
    public function getStateTextAttribute()
    {
        return State::state($this->state);
    }
    
    public function getPlainTextContentAttribute()
    {
        return trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($this->content))))));
    }

    public function getContentPreviewAttribute()
    {
        return strlen($this->plain_text_content) > 30 ? substr($this->plain_text_content,0,30)."...." : $this->plain_text_content;
    }



    public function getHtCateredAttribute($value)
    {
        if($value===1)
            return 'PASSED';
        elseif($value===0)
            return 'FAILED';
        else
            return '';
    }

    public function getHtProcessingAttribute($value)
    {
        if($value===1)
            return 'PASSED';
        elseif($value===0)
            return 'FAILED';
        else
            return '';
    }

    public function getHtSolvedAttribute($value)
    {
        if($value===1)
            return 'PASSED';
        elseif($value===0)
            return 'FAILED';
        else
            return '';
    }

    public function getHtClosedAttribute($value)
    {
        if($value===1)
            return 'PASSED';
        elseif($value===0)
            return 'FAILED';
        else
            return '';
    }
}
