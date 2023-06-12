<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    function index($name)
    {
        $info = User::with('portfolio', 'about')->where('name', $name)->first();
        return response()->json(['data' => $info]);
    }
}
