<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function requirements()
    {
        return $this->belongsToMany(Requirement::class);
    }

    public function certificateForms() {
        return $this->hasMany(CertificateForm::class);
    }
}
