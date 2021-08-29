<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    public function announcement_type(){
        return $this->belongsTo(AnnouncementType::class);
    }

    public function announcement_pictures(){
        return $this->hasMany(AnnouncementPicture::class);
    }
}
