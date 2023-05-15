<?php

namespace Tests\Feature;

use App\Models\Feed;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FeedControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        // Create two feeds with different sites
        $feed1 = Feed::factory()->create(['site' => 'https://www.pornhub.com']);
        $feed2 = Feed::factory()->create(['site' => 'https://www.pornhub2.com']);

        // Run the index method
        $response = $this->get(route('feeds.index'));

        // Assert the response status is 200
        $response->assertStatus(200);

        // Assert that the feeds are passed to the view
        $response->assertViewHas('feeds', function ($feeds) use ($feed1, $feed2) {
            // Assert that the feeds collection contains the created feeds
            return $feeds->contains($feed1) && $feeds->contains($feed2);
        });
    }

    public function test_index_no_feeds()
    {
        // Run the index method
        $response = $this->get(route('feeds.index'));

        // Assert the response status is 200 (OK)
        $response->assertStatus(200);

        // Assert that the feeds variable in the view is an empty collection
        // Feed::query()->get() returns an empty Illuminate\Database\Eloquent\Collection
        $response->assertViewHas('feeds', Feed::query()->get());
    }

    public function test_index_only_latest_feed_per_site()
    {
        // Create three feeds with the same site
        $site = 'https://www.pornhub.com';
        Feed::factory()->create([
            'site' => $site,
            'generation_date' => Carbon::now()->subDays(2)->format('Y-m-d H:i:s')
        ]);
        Feed::factory()->create([
            'site' => $site,
            'generation_date' => Carbon::now()->subDays(1)->format('Y-m-d H:i:s')
        ]);
        $lastFeed = Feed::factory()->create([
            'site' => $site,
            'generation_date' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        // Run the index method
        $response = $this->get(route('feeds.index'));

        // Assert the response status is 200 (OK)
        $response->assertStatus(200);

        // Assert that the feeds variable in the view contains only the latest feed for the site
        $response->assertViewHas('feeds', function ($feeds) use ($lastFeed) {
            return $feeds->count() === 1 && $feeds->first()->is($lastFeed);
        });
    }
}
