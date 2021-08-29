<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdinanceCategory extends Model
{
    use HasFactory;

    public function ordinances(){
        return $this->hasMany(Ordinance::class);
    }
}
