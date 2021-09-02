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

    public function complainant_lists(){
        return $this->hasMany(ComplainantList::class);
    }

    public function defendant_lists(){
        return $this->hasMany(DefendantList::class);
    }


}
