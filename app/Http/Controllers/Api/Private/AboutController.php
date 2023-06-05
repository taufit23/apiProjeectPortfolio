<?php

namespace App\Http\Controllers\Api\Private;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Str;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user()->id;
        $about = About::where('user_id', $user)->with('skill', 'pendidikan', 'sertifikasi', 'contact')->get();
        return response()->json($about);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_lahir' => 'required|',
            'alamat_ktp' => 'required|',
            'alamat_domisili' => 'required|',
            'agama' => 'required|',
            'jenis_kelamin' => 'required|',
            'summary_text' => 'required|',
            'about_text' => 'required|',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $about = new About();
        $about->user_id = auth()->user()->id;
        $about->tanggal_lahir = $request->tanggal_lahir;
        $about->alamat_ktp = $request->alamat_ktp;
        $about->alamat_domisili = $request->alamat_domisili;
        $about->agama = $request->agama;
        $about->jenis_kelamin = $request->jenis_kelamin;
        $about->summary_text = $request->summary_text;
        $about->about_text = $request->about_text;

        $avatar = $request->file('avatar');
        $image = Image::make($avatar);
        if ($image->width() > 450 or $image->height() > 450) {
            $this->setImageSize($image);
        }
        $avatarName = Str::random(8) . '.' . $avatar->getClientOriginalExtension();
        $image->save(public_path('images/about/avatar/' . $avatarName));
        $url = 'images/about/avatar/' . $avatarName;
        $about->avatar = $url;

        $cvFile = $request->file('cv_file');
        $cvFileName = Str::random(10) . '.' . $cvFile->getClientOriginalExtension();
        $cvFile->move(public_path('cv_file/'), $cvFileName);
        $about->cv_file = 'cv_file/' . $cvFileName;
        $about->save();
        return response()->json($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function setImageSize($image)
    {
        $image->resize(450, null, function ($constraint) {
            $constraint->aspectRatio();
        });
    }
}
