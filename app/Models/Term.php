<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function employees(){
        return $this->hasMany(Employee::class)->orderBy('created_at', 'DESC');
    }

}
