<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Siswa extends Authenticatable
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = [
        'id',
        'password',
        'kode_verifikasi',
        'expired',
        'is_verifikasi',
        'verifikasi',
        'created_at',
        'updated_at'
    ];
}
