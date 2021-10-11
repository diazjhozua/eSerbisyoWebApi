<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $guarded = [];

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
