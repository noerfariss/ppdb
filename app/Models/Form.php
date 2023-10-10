<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function grup()
    {
        return $this->belongsTo(GrupForm::class, 'grup_id');
    }

    public function jawaban()
    {
        return $this->hasOne(SiswaRegisterForm::class, 'form_id');
    }
}
