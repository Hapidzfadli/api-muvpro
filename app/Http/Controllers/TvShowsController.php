<?php

namespace App\Http\Controllers;

use App\Models\TvShows;
use Illuminate\Http\Request;

class TvShowsController extends Controller
{
    public function index()
    {
        return TvShows::getTvShow();
    }
}
