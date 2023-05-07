<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Search extends Model
{
    use HasFactory;
    public static function getPostsBySearch($search)
    {
        $posts = DB::table('wp_posts')
            ->distinct()
            ->select('wp_posts.ID', 'wp_postmeta.meta_value', 'wp_posts.post_name', 'wp_posts.post_title', 'wp_posts.post_content', 'wp_posts.post_type', 'wp_posts.post_content', 'wp_posts.post_date')
            ->join('wp_postmeta', 'wp_posts.ID', '=', 'wp_postmeta.post_id')
            ->where('wp_posts.post_title', 'LIKE', "%$search%")
            ->where('wp_posts.post_status', '=', 'publish')
            ->where(function ($query) {
                $query->where('wp_posts.post_type', '=', 'post')
                    ->orWhere('wp_posts.post_type', '=', 'tv');
            })
            ->where('wp_postmeta.meta_key', '=', 'IDMUVICORE_Poster')
            ->orderByDesc('wp_posts.post_date');

        return $posts;
    }
}
