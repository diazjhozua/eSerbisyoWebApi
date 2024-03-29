<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Type extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('general')
        ->logOnly(['name','model_type'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function feedbacks() {
        return $this->hasMany(Feedback::class)->orderBy('created_at', 'DESC');
    }

    public function documents() {
        return $this->hasMany(Document::class)->orderBy('created_at', 'DESC');
    }

    public function ordinances() {
        return $this->hasMany(Ordinance::class)->orderBy('created_at', 'DESC');
    }

    public function projects() {
        return $this->hasMany(Project::class)->orderBy('created_at', 'DESC');
    }

    public function complaints() {
        return $this->hasMany(Complaint::class)->orderBy('created_at', 'DESC');
    }

    public function reports() {
        return $this->hasMany(Report::class)->orderBy('created_at', 'DESC');
    }

    public function announcements() {
        return $this->hasMany(Announcement::class)->orderBy('created_at', 'DESC');
    }

}
