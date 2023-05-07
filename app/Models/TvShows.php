<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class TvShows extends Model
{
    use HasFactory;

    public static function getTvShow()
    {
        $posts = Cache::remember('tvshow', 1440, function () {
            return DB::table('wp_posts')
                ->distinct()
                ->select('wp_posts.post_title', 'wp_posts.post_date', 'wp_posts.post_name', 'wp_postmeta.meta_value')
                ->where('wp_posts.post_type', '=', 'tv')
                ->join('wp_postmeta', 'wp_posts.ID', '=', 'wp_postmeta.post_id')
                ->where('wp_postmeta.meta_key', '=', 'IDMUVICORE_Poster')
                ->orderByDesc('wp_posts.post_date')
                ->take(15)
                ->get();
        });
        return $posts;
    }
}
