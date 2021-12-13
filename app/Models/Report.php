<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Report extends Model
{
    use HasFactory, LogsActivity;

    protected $with = ['user'];

    protected static $recordEvents = ['updated'];

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('taskforce')
        ->logOnly(['type.name', 'custom_type', 'location_address', 'landmark', 'description', 'admin_message', 'is_anonymous', 'urgency_classification', 'status'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "This report has been responded");
    }


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function type(){
        return $this->belongsTo(Type::class)->withDefault();
    }

}
