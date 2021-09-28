<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function purok(){
        return $this->belongsTo(Purok::class);
    }

    public function user_role(){
        return $this->belongsTo(UserRole::class);
    }

    public function feedbacks(){
        return $this->hasMany(Feedback::class);
    }

    public function missing_persons(){
        return $this->hasMany(MissingPerson::class);
    }

    public function lost_and_founds(){
        return $this->hasMany(LostAndFound::class);
    }

    public function complaints(){
        return $this->hasMany(Complaint::class);
    }

    public function reports(){
        return $this->hasMany(Report::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function likes(){
        return $this->hasMany(Comment::class);
    }
}

