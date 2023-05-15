<?php

namespace App\Console\Commands;

use App\Jobs\CacheThumbnailImage;
use App\Models\Feed;
use Illuminate\Console\Command;

class CacheFeedImagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:feed-images {feed_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $feedId = $this->argument('feed_id');
        $feed = Feed::find($feedId);
        if (!$feed) {
            $this->error('Feed not found');
            return 1;
        }

        // chunk the pornstars into 100
        $feed->pornstars->chunk(100)->each(function ($pornstars) {
            $thumbnailsUrls = [];
            $pornstars->each(function ($pornstar) use (&$thumbnailsUrls) {
                if ($pornstar->thumbnails->count() > 0) {
                    $urls = $pornstar->thumbnails->pluck('url');
                    // flatten the array
                    $thumbnailsUrls = array_merge($urls->toArray(), $thumbnailsUrls);
                }
            });
            CacheThumbnailImage::dispatch($thumbnailsUrls)->onQueue('default');
        });

        return 0;
    }
}
