<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketReference extends Model
{
    protected $fillable=[
        'ticket_id',
        'ticket_reference_id',
        'created_by',
    ];

    public function ticket()
    {
        return $this->belongsTo('App\Ticket','ticket_id');
    }
    public function ticket_reference()
    {
        return $this->hasOne('App\Ticket','id','ticket_reference_id');
    }
}
