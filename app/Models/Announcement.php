<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    public function type(){
        return $this->belongsTo(Type::class);
    }

    public function announcement_pictures(){
        return $this->hasMany(AnnouncementPicture::class);
    }

    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
