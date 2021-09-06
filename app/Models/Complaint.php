<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function complaint_type(){
        return $this->belongsTo(ComplaintType::class)->withDefault();
    }

    public function complainants(){
        return $this->hasMany(Complainant::class);
    }

    public function defendants(){
        return $this->hasMany(Defendant::class);
    }


}
