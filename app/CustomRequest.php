<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomRequest extends Model
{
    
    protected $fillable=[
        'fields',
        'name',
        'applications',
        'field_sender_id_number',
        'field_sender_name',
        'field_sender_email',
        'field_sender_factory',
        'field_sender_phone',
        'format',
    ];
    
    
    protected $casts=[
        'fields'=>'array',
        'applications'=>'array',
    ];

}
