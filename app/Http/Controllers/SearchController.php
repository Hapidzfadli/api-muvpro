<?php

namespace App\Http\Controllers;

use App\Models\Search;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index($keyword)
    {
        return Search::getPostsBySearch($keyword)->paginate(10);
    }
}
