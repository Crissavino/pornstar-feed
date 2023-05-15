<?php

namespace Tests\Feature;

use App\Models\Feed;
use App\Models\Pornstar;
use App\Models\Thumbnail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CacheFeedImagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_cache_feed_images_command()
    {
        // Create a sample feed and associated pornstars with thumbnails
        $feed = Feed::factory()->create();
        $pornstars = Pornstar::factory()->count(3)->create(['feed_id' => $feed->id]);
        foreach ($pornstars as $pornstar) {
            Thumbnail::factory()->count(3)->create(['pornstar_id' => $pornstar->id]);
        }

        $this->artisan('cache:feed-images', ['feed_id' => $feed->id])
            ->assertExitCode(0);

        // process the queue
        $this->artisan('queue:work --once');

        // $this->assertTrue Cache has the key ('thumbnail_' . md5($thumbnail->url);
        $this->assertTrue(Cache::has('thumbnail_' . md5(Thumbnail::first()->url)));
        // check the last one too
        $this->assertTrue(Cache::has('thumbnail_' . md5(Thumbnail::latest()->first()->url)));
    }
}
