<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioCient extends Model
{
    use HasFactory;
    protected $table = 'portfolio_cients';
    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class, 'client');
    }
}
