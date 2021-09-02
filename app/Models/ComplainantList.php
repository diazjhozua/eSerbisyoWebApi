<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplainantList extends Model
{
    use HasFactory;

    public function complaint(){
        return $this->belongsTo(Complaint::class)->withDefault();
    }
}
