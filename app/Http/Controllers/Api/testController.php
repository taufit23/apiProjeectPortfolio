<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;

class testController extends Controller
{
    public function index()
    {
        return response()->json(Portfolio::all());
    }
}
