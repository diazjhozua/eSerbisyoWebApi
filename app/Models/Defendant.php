<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Defendant extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function complaint(){
        return $this->belongsTo(Complaint::class)->withDefault();
    }
}
