<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use HasFactory;
    public static function getPost()
    {
        $posts = Cache::remember('posts', 60, function () {
            return DB::table('wp_posts')
                ->distinct()
                ->select('wp_posts.post_title', 'wp_posts.post_date', 'wp_posts.post_type', 'wp_posts.post_name', 'wp_postmeta.meta_value')
                ->where('wp_posts.post_type', '=', 'post')
                ->join('wp_postmeta', 'wp_posts.ID', '=', 'wp_postmeta.post_id')
                ->where('wp_postmeta.meta_key', '=', 'IDMUVICORE_Poster')
                ->orderByDesc('wp_posts.post_date')
                ->take(15)
                ->get();
        });

        return $posts;
    }
}
