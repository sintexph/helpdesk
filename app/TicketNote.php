<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketNote extends Model
{
    protected $fillable=[
        'content',
        'created_by',
        'ticket_id',
    ];

    protected $appends=[
        'html_content'
    ];

    public function ticket()
    {
        return $this->belongsTo('App\Ticket','ticket_id');
    }
    
    public function getHtmlContentAttribute()
    {
        return nl2br($this->content);
    }

}
