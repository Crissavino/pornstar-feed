<?php

namespace App\Console\Commands;

use App\Models\Attribute;
use App\Models\Feed;
use App\Models\Pornstar;
use App\Models\Stat;
use App\Models\Thumbnail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DownloadPornstarFeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feed:download';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download the pornstar feed and cache images';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Starting the feed download command');
        // Download JSON feed
        $response = Http::acceptJson()->get('https://www.pornhub.com/files/json_feed_pornstars.json');

        if (!$response->successful()) {
            $this->error('Failed to download the feed.');
            return 1;
        }

        ini_set('memory_limit', '-1');
        $feedBody = $response->body();
        $feedData = json_decode($feedBody, true);

        $this->info('Feed downloaded successfully');
        $this->info('Feed contains ' . $feedData['itemsCount'] . ' items');
        $this->info('Feed generation date: ' . $feedData['generationDate']);
        $this->info('Feed site: ' . $feedData['site']);

        $generationDate = Carbon::parse($feedData['generationDate']);
        // get feed where the date of generation_date is equal to the date of $feedData['generationDate']
        $existFeed = Feed::where('generation_date', 'like', $generationDate->format('Y-m-d') . '%')->exists();
        // if the daily feed is already saved, then we don't need to do anything
        if ($existFeed) {
            $this->info('Feed is already up to date');
            return 0;
        }

        $feed = Feed::updateOrCreate(
            ['site' => $feedData['site']],
            [
                'generation_date' => $generationDate,
                'items_count' => $feedData['itemsCount'],
            ]
        );

        $dataChunks = collect($feedData['items'])->chunk(1000);

        $this->info(now()->format('Y-m-d H:i:s'));
        $dataChunks->each(function ($data, $index) use ($feed) {
            $this->savePornstars($data, $feed);

            $pornstars = Pornstar::where('feed_id', $feed->id)->get();
            $pornstarIds = $pornstars->pluck('id');
            $this->savePornstarAttributes($data, $pornstars);

            $attributes = Attribute::whereIn('pornstar_id', $pornstarIds)->get();
            // $attributeIds = $attributes->pluck('id');
            $this->saveStats($data, $attributes);

            $this->savePornstarThumbnails($data, $pornstars);

        });
        $this->info(now()->format('Y-m-d H:i:s'));
        $this->info('Feed downloaded and data saved successfully.');

        $this->info('Caching feed images...');
        $this->info(now()->format('Y-m-d H:i:s'));
        $this->cacheFeedImages($feed->id);
        $this->info(now()->format('Y-m-d H:i:s'));
        $this->info('Feed images cached successfully.');

        return 0;

    }

    /**
     * Save pornstars to the database using bulk insert.
     *
     * @param Collection $data
     * @param Feed $feed
     * @return void
     */
    private function savePornstars(Collection $data, Feed $feed): void
    {
        $pornstarData = $data
            ->map(function ($item) use ($feed) {
                return [
                    'feed_id' => $feed->id,
                    'name' => $item['name'],
                    'license' => $item['license'],
                    'wl_status' => $item['wlStatus'],
                    'link' => $item['link'],
                    'aliases' => json_encode($item['aliases'] ?? null),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })
            ->toArray();

        Pornstar::insert($pornstarData);
    }

    /**
     * Save attributes for pornstars using bulk insert.
     *
     * @param Collection $data
     * @param \Illuminate\Database\Eloquent\Collection $pornstars
     * @return void
     */
    private function savePornstarAttributes(Collection $data, $pornstars): void
    {
        $attributesData = $data
            ->map(function ($item, $index) use ($pornstars) {
                return [
                    'pornstar_id' => $pornstars[$index]->id,
                    'hair_color' => $item['attributes']['hairColor'] ?? null,
                    'ethnicity' => $item['attributes']['ethnicity'] ?? null,
                    'tattoos' => $item['attributes']['tattoos'],
                    'piercings' => $item['attributes']['piercings'],
                    'breast_size' => $item['attributes']['breastSize'] ?? null,
                    'breast_type' => $item['attributes']['breastType'] ?? null,
                    'gender' => $item['attributes']['gender'],
                    'orientation' => $item['attributes']['orientation'],
                    'age' => $item['attributes']['age'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })
            ->toArray();

        Attribute::insert($attributesData);
    }

    /**
    * Save stats for attributes using bulk insert.
    *
    * @param Collection $data
    * @param \Illuminate\Database\Eloquent\Collection $attributes
    * @return void
    */
    private function saveStats(Collection $data, $attributes): void
    {
        $statsData = $data
            ->map(function ($item, $index) use ($attributes) {
                return [
                    'attribute_id' => $attributes[$index]->id,
                    'subscriptions' => $item['attributes']['stats']['subscriptions'],
                    'monthly_searches' => $item['attributes']['stats']['monthlySearches'],
                    'views' => $item['attributes']['stats']['views'],
                    'videos_count' => $item['attributes']['stats']['videosCount'],
                    'premium_videos_count' => $item['attributes']['stats']['premiumVideosCount'],
                    'white_label_video_count' => $item['attributes']['stats']['whiteLabelVideoCount'],
                    'rank' => $item['attributes']['stats']['rank'],
                    'rank_premium' => $item['attributes']['stats']['rankPremium'],
                    'rank_wl' => $item['attributes']['stats']['rankWl'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })
            ->toArray();

        Stat::insert($statsData);
    }

    /**
     *
     * @param Collection $data
     * @param \Illuminate\Database\Eloquent\Collection $pornstars
     * @return void
     */
    private function savePornstarThumbnails(Collection $data, $pornstars): void
    {
        $thumbnailsData = $data
            ->flatMap(function ($item, $index) use ($pornstars) {
                return collect($item['thumbnails'])
                    ->flatMap(function ($thumbnailData) use ($pornstars, $index) {
                        return collect($thumbnailData['urls'])
                            ->map(function ($url) use ($thumbnailData, $pornstars, $index) {
                                return [
                                    'pornstar_id' => $pornstars[$index]->id,
                                    'height' => $thumbnailData['height'],
                                    'width' => $thumbnailData['width'],
                                    'type' => $thumbnailData['type'],
                                    'url' => $url,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                            });
                    });
            })
            ->toArray();

        Thumbnail::insert($thumbnailsData);
    }

    private function cacheFeedImages($feedId): void
    {
        // call the cache feed images command
        Artisan::queue('cache:feed-images', [
            'feed_id' => $feedId,
        ]);
    }

}
