<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class UserRequirement extends Model
{
    use HasFactory;

    protected $guarded = [];


    protected $with = ['requirement'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('certificate')
        ->logOnly(['user.first_name', 'user.last_name', 'requirement.name'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function requirement(){
        return $this->belongsTo(Requirement::class);
    }
}
