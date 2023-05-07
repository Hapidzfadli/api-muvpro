<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index($category, Request $request)
    {
        $page = $request->input('page') ? $request->input('page') : 1;
        $posts = Cache::remember("categoryPost" . $category . "page" . $page, '60', function () use ($category, $page) {
            return Category::getPostByCategory(strtolower($category))->paginate(10)->appends(['page' => $page]);
        });
        return $posts;
    }
}
