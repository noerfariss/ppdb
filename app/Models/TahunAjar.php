<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjar extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    public function form()
    {
        return $this->belongsToMany(Form::class, 'template_form', 'template_id', 'form_id', 'template_id')->withPivot('wajib');
    }
}
