<?php

namespace App\Http\Controllers;

use App\Models\PostDetail;
use Illuminate\Http\Request;

class PostDetailController extends Controller
{
    public function index($postname)
    {
        return PostDetail::getPostDetail($postname);
    }
}
