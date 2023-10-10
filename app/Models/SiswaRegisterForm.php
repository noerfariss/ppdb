<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaRegisterForm extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function siswa_register()
    {
        return $this->belongsTo(SiswaRegister::class, 'siswa_register_id');
    }
}
