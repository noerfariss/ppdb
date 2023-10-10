<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function form()
    {
        return $this->belongsToMany(Form::class, 'template_form', 'template_id', 'form_id')->withPivot('wajib');
    }

    public function grup()
    {
        return $this->hasManyThrough(
            GrupForm::class,
            Form::class,
            'grup_id',
            'id',
            null,
            'grup_id'
        );
    }
}
