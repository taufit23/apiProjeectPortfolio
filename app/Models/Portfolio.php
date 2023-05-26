<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;
    protected $table = "portfolios";
    public function tech()
    {
        return $this->belongsToMany(Tech::class);
    }
    public function portfolioClient()
    {
        return $this->hasOne(PortfolioCient::class, 'id');
    }
    public function getPreviewImageUrl()
    {
        return $this->preview_image = asset($this->preview_image);
    }
    public function imagesPortfolio()
    {
        return $this->belongsToMany(ImagesPortfolio::class, 'portfolio_images', 'portfolio_id', 'image_portfolio_id');
    }
}
