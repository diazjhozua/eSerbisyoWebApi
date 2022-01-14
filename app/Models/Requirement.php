<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Requirement extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('certificate')
        ->logOnly(['name'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
    }

    public function certificates() {
        return $this->belongsToMany(Certificate::class);
    }

    public function certificateForms() {
        return $this->belongsToMany(CertificateForm::class);
    }

    public function user_requirements(){
        return $this->hasMany(UserRequirement::class);
    }

}
