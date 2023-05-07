<?php

use App\Models\TvShows;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\YearController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\TvShowsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\PostDetailController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TrendingController;
use App\Http\Controllers\TvDetailController;
use App\Models\PostDetail;
use App\Models\TvDetail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/posts', [PostController::class, 'index'])->name('posts');
Route::get('/post/{postname}', [PostDetailController::class, 'index'])->name('post');
Route::get('/tvshows', [TvShowsController::class, 'index'])->name('tvshow');
Route::get('/trending', [TrendingController::class, 'index'])->name('trending');
Route::get('/categories/{category}', [CategoryController::class, 'index'])->name('categories');
Route::get("/year/{year}", [YearController::class, 'index'])->name('year');
Route::get("/country/{country}", [CountryController::class, 'index'])->name('country');
Route::get('/tv/{postname}', [TvDetailController::class, 'index'])->name('tv');
Route::get('/episode/{id}', [EpisodeController::class, 'index'])->name('episode');
Route::get('/search/{keyword}', [SearchController::class, 'index'])->name('search');
