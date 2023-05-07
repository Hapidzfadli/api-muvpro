<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class PostDetail extends Model
{
    use HasFactory;
    public static function getCategory($post_id)
    {
        $posts = Cache::remember($post_id, 10080, function () use ($post_id) {
            return DB::table('wp_term_relationships')
                ->where('wp_term_relationships.object_id', "=", "$post_id")
                ->select('wp_term_taxonomy.taxonomy', 'wp_terms.name', 'wp_terms.slug')
                ->join('wp_term_taxonomy', 'wp_term_relationships.term_taxonomy_id', '=', 'wp_term_taxonomy.term_id')
                ->join('wp_terms', 'wp_term_taxonomy.term_id', '=', 'wp_terms.term_id')
                ->get();
        });

        return $posts;
    }

    public static function getPostDetail($postname)
    {
        $result = Cache::remember($postname, 10080, function () use ($postname) {
            $posts = DB::table('wp_posts')
                ->where('wp_posts.post_type', '=', 'post')
                ->select('wp_posts.ID', 'wp_posts.post_title', 'wp_posts.post_type', 'wp_posts.post_content')
                ->where('wp_posts.post_name', '=', $postname)
                ->orderBy('wp_posts.post_date', 'asc')
                ->get()
                ->first();

            $meta_post = DB::table('wp_postmeta')
                ->distinct()
                ->select('meta_key', 'meta_value')
                ->where('post_id', '=', $posts->ID)
                ->get();


            $meta = collect();
            $meta_post->each(function ($item) use ($meta) {
                $meta[$item->meta_key] = $item->meta_value;
            });

            $result = collect();
            $result->put('ID', $posts->ID);
            $result->put('post_title', $posts->post_title);
            $result->put('post_content', $posts->post_content);
            $result->put('post_type', $posts->post_type);

            $category = collect();
            $muvicast = collect();
            $muvicountry = collect();

            $term = self::getCategory($posts->ID);
            $term->each(function ($item) use ($category, $muvicast, $muvicountry, $result) {
                if ($item->taxonomy == 'category') {
                    $category->push($item->name);
                } else if ($item->taxonomy == 'muvicast') {
                    $muvicast->push($item->name);
                } else if ($item->taxonomy == 'muvidirector') {
                    $result->put('muvidirector', $item->name);
                } else if ($item->taxonomy == 'muviquality') {
                    $result->put('muviquality', $item->name);
                } else if ($item->taxonomy == 'muvicountry') {
                    $muvicountry->push($item->name);
                } else if ($item->taxonomy == 'muviyear') {
                    $result->put('muviyear', $item->name);
                }
            });
            $result->put('category', $category);
            $result->put('muvicast', $muvicast);
            $result->put('muvicountry', $muvicountry);
            $result->put('meta_post', $meta);

            return $result;
        });

        return $result;
    }
}
