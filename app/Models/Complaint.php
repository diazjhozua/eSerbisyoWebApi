<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $with = ['user', 'contact'];

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function contact(){
        return $this->belongsTo(User::class, 'contact_user_id', 'id');
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
