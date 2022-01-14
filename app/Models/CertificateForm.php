<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateForm extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['user', 'certificate'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function requirements()
    {
        return $this->belongsToMany(Requirement::class);
    }

    public function orders() {
        return $this->belongsToMany(Order::class);
    }

    public function certificate() {
        return $this->belongsTo(Certificate::class);
    }

}
