<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketConversationAttachment extends Model
{
    protected $fillable=[
        'ticket_conversation_id',
        'file_upload_id'
    ];
    
    public function file_upload()
    {
        return $this->hasOne('App\FileUpload','id','file_upload_id');
    }
}
