<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Project extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('information')
        ->logOnly(['type.name', 'custom_type', 'name','description', 'cost', 'project_start', 'project_end', 'location', 'pdf_name', 'file_path'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
    }

    public function type(){
        return $this->belongsTo(Type::class);
    }
}
