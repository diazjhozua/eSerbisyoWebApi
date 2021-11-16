<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Feedback extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'feedbacks';

    protected $with = ['user'];

    protected static $recordEvents = ['updated'];


    protected $guarded = [];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('information')
        ->logOnly(['type.name', 'custom_term', 'polarity', 'message', 'admin_respond', 'status', 'is_anonymous'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "This feedback has been responded");
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];


    public function type() {
        return $this->belongsTo(Type::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
