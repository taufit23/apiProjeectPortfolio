<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagesPortfolio extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "images_portfolios";
    public function portfolio()
    {
        return $this->belongsToMany(Portfolio::class, 'portfolio_images');
    }
    public function getImageUrl()
    {
        return $this->image = asset($this->image);
    }
}
