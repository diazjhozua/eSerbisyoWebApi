<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    public function requirements()
    {
        return $this->belongsToMany(Requirement::class)
            ->withTimestamp();
    }
}
