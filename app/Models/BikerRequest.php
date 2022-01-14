<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class BikerRequest extends Model
{
    use HasFactory, LogsActivity;

    protected $with = ['user'];
    protected $guarded = [];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('certificate')
        ->logOnly(['user.first_name', 'user.last_name', 'bike_type', 'bike_size', 'bike_color', 'status', 'admin_message'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
    }


    public function user(){
        return $this->belongsTo(User::class);
    }
}
