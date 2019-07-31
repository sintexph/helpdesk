<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    protected $fillable=[
        'ticket_id',
        'file_upload_id'
    ];
    
    public function file_upload()
    {
        return $this->hasOne('App\FileUpload','id','file_upload_id');
    }

}
