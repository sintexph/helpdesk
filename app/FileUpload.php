<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Sintex\Helper\FileHelper;

class FileUpload extends Model
{
    protected $fillable=[
        'file_name',
        'file_type',
        'file_path',
        'file_mime',
        'file_size',
        'uploaded_by',
        'has_preview',
    ];

    protected $casts=[
        'has_preview'=>'boolean',
        
    ];
    protected $appends=['url'];


    public function getFileSizeAttribute($value)
    {
        return FileHelper::formatSizeUnits($value);
    }
    
    public function getUrlAttribute()
    {
        return route('get_uploaded_file',$this->id);
    }
    
}
