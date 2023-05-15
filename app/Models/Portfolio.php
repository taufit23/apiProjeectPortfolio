<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;
    public function tech()
    {
        return $this->belongsToMany(Tech::class);
    }
    public function portfolioClient()
    {
        return $this->hasOne(PortfolioCient::class, 'id');
    }
}
