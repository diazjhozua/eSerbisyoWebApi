<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $with = ['user'];

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function type(){
        return $this->belongsTo(Type::class);
    }

    public function complainants(){
        return $this->hasMany(Complainant::class);
    }

    public function defendants(){
        return $this->hasMany(Defendant::class);
    }


}
