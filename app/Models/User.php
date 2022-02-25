<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'picture_name',
        'file_path',
        'address',
        'created_at',
        'updated_at',
        'status',
        'admin_status_message',
        'is_verified',
        'user_role_id',
        'purok_id',
        'email',
        'password',
        'phone_no',
        'bike_type',
        'bike_color',
        'bike_size',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = ['user_role'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('information')
        ->logOnly(['email', 'first_name',  'middle_name', 'last_name', 'address', 'picture_name', 'file_path', 'is_verified', 'status', 'admin_status_message', 'user_role.role'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
    }

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

    public function latest_user_verification(){
        return $this->hasOne(UserVerification::class)->latestOfMany();
    }

    public function latest_biker_request(){
        return $this->hasOne(BikerRequest::class)->latestOfMany();
    }

    public function bikers_requests(){
        return $this->hasMany(BikerRequest::class);
    }

    public function user_requirements(){
        return $this->hasMany(UserRequirement::class);
    }

    public function feedbacks(){
        return $this->hasMany(Feedback::class);
    }

    public function orders(){
        return $this->hasMany(Order::class, 'ordered_by', 'id');
    }

    public function orderSuccess(){
        return $this->hasMany(Order::class, 'ordered_by', 'id')->where('order_status', 'Received');
    }

    public function orderReports(){
        return $this->hasMany(OrderReport::class);
    }

    public function delivers(){
        return $this->hasMany(Order::class, 'delivered_by', 'id');
    }

    public function deliverySuccess(){
        return $this->hasMany(Order::class, 'delivered_by', 'id')->where('order_status', 'Received')->where('delivery_payment_status', 'Received');
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

    public function certificateForms(){
        return $this->hasMany(CertificateForm::class);
    }


    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }
}

