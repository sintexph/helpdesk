<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectAttachment extends Model
{
    protected $fillable=[
        'project_id',
        'file_upload_id'
    ];
    
    public function file_upload()
    {
        return $this->hasOne('App\FileUpload','id','file_upload_id');
    }
    public function project()
    {
        return $this->belongsTo('App\Project','project_id');
    }
}
