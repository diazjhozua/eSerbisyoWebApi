<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackType extends Model
{
    use HasFactory;

    public function feedbacks(){
        return $this->hasMany(Feedback::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
