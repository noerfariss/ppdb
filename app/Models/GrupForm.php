<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupForm extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function form()
    {
        return $this->hasMany(Form::class, 'grup_id');
    }
}
