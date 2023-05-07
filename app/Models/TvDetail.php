<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TvDetail extends Model
{
    use HasFactory;
    public static function getIdPostEpisode($tmdbID)
    {
        $postsID = DB::table('wp_postmeta')
            ->distinct()
            ->select('wp_postmeta.post_id', 'postmeta.meta_key', 'postmeta.meta_value')
            ->join('wp_posts', 'wp_postmeta.post_id', '=', 'wp_posts.ID')
            ->where('wp_postmeta.meta_value', '=', $tmdbID)
            ->where('wp_posts.post_type', '=', "episode")
            ->join('wp_postmeta as postmeta', 'wp_posts.ID', '=', 'postmeta.post_id')
            ->where('postmeta.meta_key', '=', 'IDMUVICORE_Episodenumber')
            ->get();
        return $postsID;
    }

    public static function getPostEpisode($post)
    {
        $postsID = DB::table('wp_postmeta')
            ->distinct()
            ->select('wp_postmeta.post_id', 'postmeta.meta_key', 'postmeta.meta_value')
            ->join('wp_posts', 'wp_postmeta.post_id', '=', 'wp_posts.ID')
            ->where('wp_postmeta.post_id', '=', $post)
            ->where('wp_posts.post_type', '=', "episode")
            ->join('wp_postmeta as postmeta', 'wp_posts.ID', '=', 'postmeta.post_id')
            ->get();

        $result = collect([]);

        $postsID->each(function ($item1, $index1) use ($result) {
            $result->put('post_id', $item1->post_id);
            $result->put($item1->meta_key, $item1->meta_value);
        });

        return $result;
    }

    public static function getEpisode($postID)
    {
        $postsID = DB::table('wp_postmeta')
            ->distinct()
            ->select('wp_postmeta.post_id', 'postmeta.meta_key', 'postmeta.meta_value')
            ->join('wp_posts', 'wp_postmeta.post_id', '=', 'wp_posts.ID')
            ->where('wp_postmeta.post_id', '=', $postID)
            ->where('wp_posts.post_type', '=', "episode")
            ->join('wp_postmeta as postmeta', 'wp_posts.ID', '=', 'postmeta.post_id')
            ->get();


        $uniqId = collect();
        $postsID->each(function ($item, $index) use ($uniqId) {
            if (!$uniqId->contains($item->post_id)) {
                $uniqId->push($item->post_id);
            }
        });

        $uniqId = $uniqId->unique();

        $result = collect([]);
        $postsID->each(function ($item1, $index1) use ($result, $uniqId) {
            $uniqId->each(function ($item2, $index2) use ($result, $item1) {
                if ($item1->post_id == $item2) {
                    if (!isset($result[$item2])) {
                        $result[$item2] = collect();
                        $result[$item2]->put('post_id', $item1->post_id);
                    }
                    $result[$item2]->put($item1->meta_key, $item1->meta_value);
                }
            });
        });
        return $result;
    }

    public static function getTvDetail($postname)
    {
        $posts = DB::table('wp_posts')
            ->where('wp_posts.post_type', '=', 'tv')
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

        $episode = self::getIdPostEpisode($meta['IDMUVICORE_tmdbID']);

        $result = collect();
        $result->put('ID', $posts->ID);
        $result->put('post_title', $posts->post_title);
        $result->put('post_content', $posts->post_content);
        $result->put('post_type', $posts->post_type);

        $category = collect();
        $muvicast = collect();
        $muvicountry = collect();

        $term = PostDetail::getCategory($posts->ID);
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

        $result->put('episode', $episode);
        return $result;
    }
}
