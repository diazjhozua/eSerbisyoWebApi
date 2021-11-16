<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Android extends Model
{

    use HasFactory, LogsActivity;
    protected $guarded = [];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('information')
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
    }
}
