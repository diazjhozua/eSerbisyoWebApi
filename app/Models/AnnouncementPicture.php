<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementPicture extends Model
{
    use HasFactory;

    public function announcement(){
        return $this->belongsTo(Announcement::class);
    }
}
