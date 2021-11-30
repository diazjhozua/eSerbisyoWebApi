<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MissingPerson extends Model
{
    use HasFactory;
    protected $table = 'missing_persons';
    protected $with = ['user', 'contact'];

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function contact(){
        return $this->belongsTo(User::class, 'contact_user_id', 'id');
    }

    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
