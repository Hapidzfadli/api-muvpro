<?php

namespace App\Http\Controllers;

use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class YearController extends Controller
{
    public function index($year, Request $request)
    {
        $page = $request->input('page') ? $request->input('page') : 1;
        $posts = Cache::remember("yearPost" . $year . "page" . $page, '60', function () use ($year, $page) {
            return Year::getPostByYear(strtolower($year))->paginate(10)->appends(['page' => $page]);
        });
        return $posts;
    }
}
