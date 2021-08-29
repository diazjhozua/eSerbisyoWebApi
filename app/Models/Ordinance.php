<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordinance extends Model
{
    use HasFactory;

    public function ordinance_category(){
        return $this->belongsTo(OrdinanceCategory::class);
    }
}
