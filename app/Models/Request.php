<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function requirements()
    {
        return $this->belongsToMany(Requirement::class);
    }

    public function orders() {
        return $this->belongsToMany(Order::class);
    }

    public function certificate() {
        return $this->belongsTo(Certificate::class);
    }
}
