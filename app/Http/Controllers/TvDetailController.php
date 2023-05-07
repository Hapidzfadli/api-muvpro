<?php

namespace App\Http\Controllers;

use App\Models\TvDetail;
use Illuminate\Http\Request;

class TvDetailController extends Controller
{
    public function index($postname)
    {
        return TvDetail::getTvDetail($postname);
    }
}
