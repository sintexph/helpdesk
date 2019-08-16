<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CannedSolution extends Model
{
    protected $fillable=[
        'name',
        'content',
        'type',
        'created_by',
        'updated_by',
    ];

    protected $appends=[
        'content_html'
    ];
    
    public function getContentHtmlAttribute()
    {
        return nl2br($this->content);
    }
}
