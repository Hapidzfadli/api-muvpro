<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;

    public static function getPostByCategory($category)
    {
        $posts = DB::table('wp_terms')
            ->distinct()
            ->where('wp_terms.name', '=', $category)
            ->join('wp_term_relationships', 'wp_terms.term_id', '=', 'wp_term_relationships.term_taxonomy_id')
            ->join('wp_posts', 'wp_term_relationships.object_id', '=', 'wp_posts.ID')
            ->join('wp_postmeta', 'wp_posts.ID', '=', 'wp_postmeta.post_id')
            ->select('wp_posts.post_title', 'wp_posts.post_content', 'wp_posts.post_name', 'wp_postmeta.meta_value', 'wp_posts.post_type', 'wp_posts.post_date')
            ->where('wp_postmeta.meta_key', '=', 'IDMUVICORE_Poster')
            ->orderByDesc('wp_posts.post_date');
        return $posts;
    }
}
