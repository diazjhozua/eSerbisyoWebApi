<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Complaint extends Model
{
    use HasFactory, LogsActivity;

    protected $with = ['user', 'contact'];

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('taskforce')
         ->logOnly(['user.first_name', 'user.last_name','user_id', 'contact.first_name', 'contact.last_name', 'contact_user_id',
         'type.name', 'custom_type', 'email', 'phone_no', 'reason', 'action', 'status', 'admin_message'
         ])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
    }


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function contact(){
        return $this->belongsTo(User::class, 'contact_user_id', 'id');
    }

    public function type(){
        return $this->belongsTo(Type::class);
    }

    public function complainants(){
        return $this->hasMany(Complainant::class);
    }

    public function defendants(){
        return $this->hasMany(Defendant::class);
    }


}
