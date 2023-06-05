<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    public function about()
    {
        return $this->belongsTo(About::class);
    }
    public function contacType()
    {
        return $this->hasOne(Contact_type::class);
    }
}
