<?php

namespace App\Http\Controllers;

use App\Models\TvDetail;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    public function index($postid)
    {
        return TvDetail::getPostEpisode($postid);
    }
}
