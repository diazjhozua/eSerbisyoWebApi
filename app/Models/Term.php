<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Term extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('information')
        ->logOnly(['name', 'year_start', 'year_end'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
    }

    public function employees(){
        return $this->hasMany(Employee::class)->select('employees.*')->join('positions', 'employees.position_id', 'positions.id')
        ->orderBy('positions.ranking', 'asc');

        //     return $this->hasMany(ReportFields::class)
        // ->select('report_fields.*')
        // ->join('fields', 'report_fields.field_id', 'fields.id')
        // ->orderBy('fields.position', 'asc');
    }

}
