<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\DateTrait;

class TicketConversation extends Model
{ 
    
    protected $fillable=[
        'ticket_id',
        'content',
        'created_by',
    ];

    protected $appends=[
        'content_html',
        'creator_initials',
        'time_ago'
    ];


    public function getTimeAgoAttribute()
    {
        $date=new \Carbon\Carbon($this->created_at);
        return $date->diffForHumans(); 
    }


    public function getContentHtmlAttribute()
    {
        return \nl2br($this->content);
    }
    public function getCreatorInitialsAttribute()
    {
        $words=explode(" ",$this->creator->name);
        
        if(count($words)>=2)
            return strtoupper(substr($words[0],0,1).substr($words[1],0,1));
        else {
             return strtoupper(substr($words[0],0,2));
        }  
    }

    public function creator()
    {
        return $this->belongsTo('App\User','created_by');
    }

    public function attachments()
    {
        return $this->hasMany('App\TicketConversationAttachment','ticket_conversation_id');
    }

    public function ticket()
    {
        return $this->belongsTo('App\Ticket','ticket_id');
    }
}
