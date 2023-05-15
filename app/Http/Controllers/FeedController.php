<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        // get the last feed for each site, only one feed per site
        $feeds = Feed::latest('generation_date')->get()->unique('site');

        return view('feed.index', [
            'feeds' => $feeds,
        ]);
    }
}
