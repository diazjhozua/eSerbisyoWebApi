<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function certificates() {
        return $this->belongsToMany(Certificate::class);
    }

    public function certificateForms() {
        return $this->belongsToMany(CertificateForm::class);
    }
}
