<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user()
    {
        return $this->hasOne(User::class);
    }
    public function skill()
    {
        return $this->hasMany(Skill::class);
    }
    public function contact()
    {
        return $this->hasMany(Contact::class);
    }
    public function sertifikasi()
    {
        return $this->hasMany(Sertifikasi::class);
    }
    public function pendidikan()
    {
        return $this->hasMany(Pendidikan::class);
    }
    public function getImageUrl()
    {
        return $this->avatar = asset($this->avatar);
    }
}
