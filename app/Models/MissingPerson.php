<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class MissingPerson extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'missing_persons';
    protected $with = ['user', 'contact'];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('taskforce')
         ->logOnly(['user.first_name', 'user.last_name','user_id', 'contact.first_name', 'contact.last_name', 'contact_user_id',
         'height', 'height_unit', 'weight', 'weight_unit', 'age', 'eyes', 'hair', 'unique_sign', 'important_information', 'last_seen', 'email',
         'phone_no', 'picture_name', 'file_path', 'status', 'admin_message', 'report_type', 'credential_name', 'credential_file_path'
         ])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
    }

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function contact(){
        return $this->belongsTo(User::class, 'contact_user_id', 'id');
    }

    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
