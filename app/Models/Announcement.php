<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Announcement extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('information')
        ->logOnly(['type.name', 'custom_type', 'title','description'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
    }

    public function type(){
        return $this->belongsTo(Type::class);
    }

    public function announcement_pictures(){
        return $this->hasMany(AnnouncementPicture::class);
    }

    public function comments() {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likes() {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function selfLike()
    {
        if($this->likes->contains('user_id', auth('api')->user()->id)) {
            return true;
        } else {
            return false;
        }
    }
}
