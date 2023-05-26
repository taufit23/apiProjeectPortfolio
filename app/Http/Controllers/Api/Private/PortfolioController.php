<?php

namespace App\Http\Controllers\Api\Private;

use App\Http\Controllers\Controller;
use App\Http\Requests\Private\CreatePortfolioRequest;
use App\Models\ImagesPortfolio;
use App\Models\Portfolio;
use App\Models\PortfolioCient;
use App\Models\Tech;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $portfolio = Portfolio::with('tech', 'portfolioClient', 'imagesPortfolio')->paginate(10);
        foreach ($portfolio as $key => $port) {
            $port->getPreviewImageUrl();
            foreach ($port->imagesPortfolio as $key => $img) {
                $img->getImageUrl();
            }
        }
        return response()->json($portfolio);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' =>  'required|string',
            'title' =>  'required|string|unique:portfolios,title',
            'client_type' =>  'required|string',
            'client_name' =>  'required|string',
            'preview_url' =>  'required|url',
            'summary' =>  'required|string|min:20|max:80',
            'content' =>  'required|string|min:50|max:10000',
            'start_date' => 'required|date_format:Y/m/d',
            'end_date' => 'required|date_format:Y/m/d',
            'tech' => 'required|array',
            'desc' => 'required|array',
            'preview_image' => 'required|mimes:png,jpg,jpeg',
            'image' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([$validator->errors(), 401]);
        }
        // DONE: new tech list dibuat berdasarkan title dan id dimasukan ke $techidList seleteha itu baru di sync
        $teches = $request->tech;
        $techidList = [];
        foreach ($teches as $techess) {
            $techFind = Tech::where('title', $techess)->first();
            if (!$techFind) {
                $cat = new Tech();
                $cat->title = $techess;
                $cat->save();
                $techidList[] = $cat->id;
            } else {
                $techidList[] = $techFind->id;
            }
        }
        $portfolio = new Portfolio();
        $portfolio->type = $request->type;
        $portfolio->title = $request->title;
        $portfolio->slug = Str::slug($request->title);
        // DONE: Client dibuat di database yang berbeda
        $portfolioClient = new PortfolioCient();
        $portfolioClient->name = $request->client_name;
        $portfolioClient->type = $request->client_type;
        $portfolioClient->save();
        $portfolio->client = $portfolioClient->id;
        $portfolio->preview_url = $request->preview_url;
        $portfolio->summary = $request->summary;
        $portfolio->content = $request->content;
        $portfolio->start_date = $request->start_date;
        $portfolio->end_date = $request->end_date;
        // Singe Image
        $file = $request->file('preview_image');
        $image = Image::make($file);
        if ($image->width() > 450 or $image->height() > 450) {
            $this->setImageSize($image);
        }
        //SAVE
        $fileName = Str::random(7) . '.' . $file->getClientOriginalExtension(); // random string + okstensi original file
        $image->save(public_path('images/portfolio/' . $fileName));
        $url = 'images/portfolio/' . $fileName;
        $portfolio->preview_image = $url;

        // Multiple Image
        $images = $request->file('image');
        $imagesIdArray = [];
        foreach ($images as $img => $index) {
            $picture = Image::make($index);
            if ($picture->width() > 450 or $picture->height() > 450) {
                $this->setImageSize($picture);
            }
            $pictName = Str::random(3) . '.' . $index->getClientOriginalExtension();
            $picture->save(public_path('images/portfolio/' . $pictName));
            $pictures = new ImagesPortfolio();
            $pictures->image = "images/portfolio/" . $pictName;
            $pictures->desc = $request->desc[$img];
            $pictures->save();
            $imagesIdArray[] = $pictures->id;
        }
        $portfolio->save();
        $portfolio->imagesPortfolio()->sync($imagesIdArray);
        $portfolio->tech()->sync($techidList);
        return response()->json($portfolio->load('tech', 'portfolioClient', 'imagesPortfolio'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $portfolio = Portfolio::where('slug', $slug)->with('tech', 'portfolioClient', 'imagesPortfolio')->first();
        if ($portfolio->preview_image != null) {
            $portfolio->getPreviewImageUrl();
        }
        if ($portfolio->imagesPortfolio != null) {
            foreach ($portfolio->imagesPortfolio as $img) {
                $img->getImageUrl();
            }
        }
        return response()->json($portfolio);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Portfolio $portfolio)
    {
        $teches = $request->tech;
        $techidList = [];
        foreach ($teches as $techess) {
            $techFind = Tech::where('title', $techess)->first();
            if (!$techFind) {
                $cat = new Tech();
                $cat->title = $techess;
                $cat->save();
                $techidList[] = $cat->id;
            } else {
                $techidList[] = $techFind->id;
            }
        }
        $portfolio->type = $request->type;
        $portfolio->title = $request->title;
        $portfolio->slug = Str::slug($request->title);
        // DONE: Client dibuat di database yang berbeda

        $portfolioClient = PortfolioCient::find($portfolio->client);
        $portfolioClient->name = $request->client_name;
        $portfolioClient->type = $request->client_type;

        $portfolioClient->save();
        $portfolio->client = $portfolioClient->id;
        $portfolio->preview_url = $request->preview_url;
        $portfolio->summary = $request->summary;
        $portfolio->content = $request->content;
        $portfolio->start_date = $request->start_date;
        $portfolio->end_date = $request->end_date;
        if ($request->hasFile('preview_image')) {
            $file = $request->file('preview_image');
            $image = Image::make($file);
            if ($image->width() > 450 or $image->height() > 450) {
                $image->resize(450, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            //SAVE
            $fileName = Str::random(7) . '.' . $file->getClientOriginalExtension(); // random string + okstensi original file
            $image->save(public_path('images/portfolio/' . $fileName));
            $url = 'images/portfolio/' . $fileName;

            $portfolio->preview_image = $url;
        }
        // Multiple Image
        $imagesIdArray = [];
        if ($request->hasFile('image')) {
            $images = $request->file('image');
            foreach ($images as $img => $index) {
                $picture = Image::make($index);
                if ($picture->width() > 450 or $picture->height() > 450) {
                    $this->setImageSize($picture);
                }
                $pictName = Str::random(3) . '.' . $index->getClientOriginalExtension();
                $picture->save(public_path('images/portfolio/' . $pictName));
                $pictures = new ImagesPortfolio();
                $pictures->image = "images/portfolio/" . $pictName;
                $pictures->desc = $request->desc[$img];
                $pictures->save();
                $imagesIdArray[] = $pictures->id;
            }
        }
        $portfolio->save();
        $portfolio->imagesPortfolio()->attach($imagesIdArray);
        $portfolio->tech()->sync($techidList);
        return response()->json($portfolio->load('tech', 'portfolioClient', 'imagesPortfolio'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Portfolio $portfolio)
    {
        $portfolio->tech()->detach();
        $portfolio->portfolioClient()->detach();
        $portfolio->imagesPortfolio()->detach();
        $portfolio->tech()->detach();
        $portfolio->delete();
        return response()->json(['message' => 'Portfolio Deleted', 'Delected Data' => $portfolio]);
    }
    public function setImageSize($image)
    {
        $image->resize(450, null, function ($constraint) {
            $constraint->aspectRatio();
        });
    }
}
