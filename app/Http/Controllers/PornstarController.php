<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use App\Models\Pornstar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PornstarController extends Controller
{
    public function index(Request $request, $feed_id)
    {
        // get the feed by id
        $feed = Feed::findOrFail($feed_id);
        $device = $this->getDevice();

        // with Thumbnails for the device type, get only one thumbnail per pornstar
        $pornstars = $feed
            ->pornstars()
            ->with([
                'Attributes.Stats',
                'Thumbnails'
            ])
            ->paginate(20);

        return view('pornstars.index', [
            'pornstars' => $pornstars,
            'feed_id' => $feed_id,
            'device' => $device,
        ]);
    }

    /**
     * @return string
     */
    private function getDevice(): string
    {
        $user_agent = Request::capture()->header('User-Agent');
        // check user device
        $device = 'pc';
        if (preg_match('/(android|iphone|ipad|mobile)/i', $user_agent)) {
            $device = 'mobile';
        }
        if (preg_match('/(ipad|tablet)/i', $user_agent)) {
            $device = 'tablet';
        }
        return $device;
    }

    public function show(Request $request, $feed_id, $pornstar_id)
    {
        // get the pornstar by id
        $pornstar = Pornstar::findOrFail($pornstar_id);
        $device = $this->getDevice();

        $pornstar->attributes->stats->rank = $this->formatNumber($pornstar->attributes->stats->rank);
        $pornstar->attributes->stats->views = $this->formatNumber($pornstar->attributes->stats->views);
        $pornstar->attributes->stats->subscriptions = $this->formatNumber($pornstar->attributes->stats->subscriptions);

        // check if next and previous pornstar exists
        $next_pornstar = Pornstar::find($pornstar_id + 1);
        $previous_pornstar = Pornstar::find($pornstar_id - 1);

        return view('pornstars.show', [
            'device' => $device,
            'pornstar' => $pornstar,
            'feed_id' => $feed_id,
            'next_pornstar_id' => $next_pornstar ? $pornstar_id + 1 : null,
            'previous_pornstar_id' => $previous_pornstar ? $pornstar_id - 1 : null,
        ]);
    }

    private function formatNumber($number): string
    {
        // if the number is greater than 1 million, then we format it to 1.2M
        if ($number > 1000000) {
            return number_format($number / 1000000, 1) . 'M';
        }

        // if the number is greater than 1 thousand, then we format it to 1.2K
        if ($number > 1000) {
            return number_format($number / 1000, 1) . 'K';
        }

        return strval($number);
    }

}
