<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Order extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('certificate')
        ->logOnly([
            'ordered_by', 'contact.first_name', 'contact.last_name', 'delivered_by', 'biker.first_name', 'biker.last_name', 'name', 'email', 'phone_no', 'location_address',
            'total_price', 'delivery_fee', 'pickup_date', 'application_status', 'pick_up_type', 'order_status','admin_message'
        ])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "This model has been {$eventName}");
    }
    protected $with = ['contact', 'biker'];

    public function certificateForms()
    {
        return $this->belongsToMany(CertificateForm::class);
    }

    public function contact(){
        return $this->belongsTo(User::class, 'ordered_by', 'id');
    }

    public function biker(){
        return $this->belongsTo(User::class, 'delivered_by', 'id');
    }

}
