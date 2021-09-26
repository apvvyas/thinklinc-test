<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\YoutubeVideo;
use Illuminate\Http\Response;

class SearchController extends Controller
{
    function search(Request $request)
    {
        return response()->json([
            'message' => __('video_search.list'),
            'results' => YoutubeVideo::search($request->get('search'))->orderBy('published_at', 'desc')->paginate(20)
        ]);
    }

    function show(Request $request, $youtube)
    {
        $youtube = YoutubeVideo::findVideo($youtube)->first();

        if($youtube){
            return response()->json([
                'message'   =>  __('video_search.entry'),
                'result'    => $youtube
            ]);
        }

        return response()->json([
            'message' =>  __('video_search.no_entry')
        ], Response::HTTP_NOT_FOUND );
    }
}
