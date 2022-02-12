<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class UserVerification extends Model
{
    use HasFactory, LogsActivity;

    protected $with = ['user'];
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('information')
        ->logOnly(['credential_name', 'credential_file_path','status', 'admin_message'])
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
