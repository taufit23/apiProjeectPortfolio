<?php

namespace App\Observers;

use App\Models\Portfolio;
use Illuminate\Support\Facades\File;

class PortfolioObserver
{
    /**
     * Handle the Portfolio "created" event.
     */
    public function created(Portfolio $portfolio): void
    {
        //
    }

    /**
     * Handle the Portfolio "updated" event.
     */
    public function updated(Portfolio $portfolio): void
    {
        //
    }

    /**
     * Handle the Portfolio "deleted" event.
     */
    public function deleted(Portfolio $portfolio): void
    {
        File::delete(public_path($portfolio->preview_image));
        $images = $portfolio->imagesPortfolio->pluck('image')->toArray();
        // ambil nama file dari setiap gambar dan jadikan array
        $imagePaths = array_map(function ($image) {
            return public_path($image);
        }, $images);
        // buat array berisi jalur lengkap untuk setiap gambar
        File::delete($imagePaths);

        $portfolio->tech()->detach();
        $portfolio->imagesPortfolio()->delete();
        $portfolio->imagesPortfolio()->detach();
        // hapus semua gambar dalam array tersebut
    }

    /**
     * Handle the Portfolio "restored" event.
     */
    public function restored(Portfolio $portfolio): void
    {
        //
    }

    /**
     * Handle the Portfolio "force deleted" event.
     */
    public function forceDeleted(Portfolio $portfolio): void
    {
        //
    }
}
