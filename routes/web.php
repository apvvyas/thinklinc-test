<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Arr;
use Carbon\Carbon;
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
    //dd(now()->format('Y-m-d\TH:i:s\Z'));
    dd(collect(\Youtube::paginateResults([
        'q'             => 'Android',
        'type'          => 'video',
        'part'          => 'id, snippet',
        'maxResults'    => 50
    ])['results'])->map(function($item){
        return [
            'youtube_id' => $item->id->videoId,
            'title' => $item->snippet->title,
            'description' => $item->snippet->description,
            'thumbnails' => $item->snippet->thumbnails,
            'published_at' => Carbon::parse($item->snippet->publishedAt),
            'etag' => $item->etag
        ];
    })->last());
});
