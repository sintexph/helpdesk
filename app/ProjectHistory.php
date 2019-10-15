<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ProjectHistoryHelper;

class ProjectHistory extends Model
{
    protected $fillable=[
        'type',
        'description',
        'field',
        'creator',
        'project_id',
    ];

    protected $appends=[
        'created_time',
        'created_date',
        'type_text',
        'icon',
    ];

    public function getCreatorAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    public function getCreatedTimeAttribute()
    {
        return $this->created_at->format('h:m a');
    }
    public function getCreatedDateAttribute()
    {
        return $this->created_at->format('M d, Y ');
    }

    public function getTypeTextAttribute()
    {
        return ProjectHistoryHelper::type($this->type);
    }
    public function getIconAttribute()
    {
        return ProjectHistoryHelper::icon($this->type);
    }
}
