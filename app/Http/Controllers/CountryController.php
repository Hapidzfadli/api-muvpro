<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CountryController extends Controller
{
    public function index($country, Request $request)
    {
        $page = $request->input('page') ? $request->input('page') : 1;
        $posts = Cache::remember("countryPost" . $country . "page" . $page, '60', function () use ($country, $page) {
            return Country::getPostByCountry(strtolower($country))->paginate(10)->appends(['page' => $page]);
        });

        return $posts;
    }
}
