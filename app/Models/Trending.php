<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class Trending extends Model
{
    use HasFactory;

    public static function getPostTrending()
    {
        $posts = Cache::remember('trending', 1140, function () {
            return DB::table('wp_posts')
                ->distinct('id')
                ->select('wp_posts.post_title', 'wp_post_views.count', 'wp_posts.post_type', 'wp_posts.post_name', 'wp_postmeta.meta_value')
                ->join('wp_post_views', 'wp_posts.ID', '=', 'wp_post_views.id')
                ->where('wp_posts.post_type', '=', 'post')
                ->where('wp_post_views.type', '=', '4')
                ->join('wp_postmeta', 'wp_posts.ID', '=', 'wp_postmeta.post_id')
                ->where('wp_postmeta.meta_key', '=', 'IDMUVICORE_Poster')
                ->whereBetween('wp_posts.post_date', [Carbon::now()->subMonth(1), Carbon::now()])
                ->orderByDesc('wp_post_views.count')
                ->take(15)
                ->get();
        });

        return $posts;
    }
}
