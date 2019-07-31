<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable=[
        'name',
        'value',
        'created_by',
        'updated_by',
        'remark',
    ];
}
