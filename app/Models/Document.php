<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function type(){
        return $this->belongsTo(Type::class);
    }

    // public function document_type(){
    //     return $this->belongsTo(DocumentType::class);
    // }
}
