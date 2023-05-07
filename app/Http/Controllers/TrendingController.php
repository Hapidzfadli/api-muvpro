<?php

namespace App\Http\Controllers;

use App\Models\Trending;
use Illuminate\Http\Request;

class TrendingController extends Controller
{
    public function index()
    {
        return Trending::getPostTrending();
    }
}
