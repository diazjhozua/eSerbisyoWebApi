<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Inquiry extends Model
{
    use HasFactory, LogsActivity;

    protected static $recordEvents = ['updated'];

    protected $with = ['user'];

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('information')
        ->logOnly(['about', 'message', 'admin_message', 'status'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "This inquiry has been responded");
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
