<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function parent()
    {
        return $this->belongsTo(Category::class);
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function post()
    {
        return $this->belongsToMany(Post::class);
    }
    public function portfolio()
    {
        return $this->belongsToMany(Portfolio::class);
    }
}
