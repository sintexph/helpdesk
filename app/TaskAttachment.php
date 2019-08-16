<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskAttachment extends Model
{
    protected $fillable=[
        'task_id',
        'file_upload_id'
    ];
    
    public function file_upload()
    {
        return $this->hasOne('App\FileUpload','id','file_upload_id');
    }
    public function task()
    {
        return $this->belongsTo('App\Task','task_id');
    }
}
