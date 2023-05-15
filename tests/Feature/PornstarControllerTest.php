<?php

namespace Tests\Feature;

use App\Models\Attribute;
use App\Models\Feed;
use App\Models\Pornstar;
use App\Models\Stat;
use App\Models\Thumbnail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PornstarControllerTest extends TestCase
{

    use RefreshDatabase;

    public function test_index_returns_view_with_paginated_pornstars()
    {
        // Create a feed with some pornstars
        $feed = Feed::factory()->create([
            'site' => 'https://www.pornhub.com',
            'generation_date' => now()->format('Y-m-d H:i:s'),
            'items_count' => 30
        ]);
        $pornstars = $this->createPornstarsAndRelations($feed, 30);

        // Mock the User-Agent header for a pc browser
        $response = $this
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) ' .
                    'AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36'
            ])
            ->get(route('pornstars.index', ['feed_id' => $feed->id]))
            ->assertStatus(200);

        // Assert that the view contains the first 20 pornstars paginated and the 21st pornstar is not present on the view
        $response
            ->assertViewIs('pornstars.index')
            ->assertViewHas('pornstars')
            ->assertViewHas('feed_id', $feed->id)
            ->assertSee($pornstars[0]->name)
            ->assertSee($pornstars[19]->name)
            ->assertDontSee($pornstars[20]->name);
    }

    public function test_index_returns_empty_view_when_no_pornstars_available()
    {
        // Create a feed without any pornstars
        $feed = Feed::factory()->create();

        $this->get(route('pornstars.index', $feed->id))
            ->assertStatus(200)
            ->assertViewIs('pornstars.index')
            ->assertViewHas('pornstars')
            ->assertViewHas('feed_id', $feed->id)
            ->assertSee('No pornstars found.');
    }

    public function test_show_returns_view_with_pornstar_details()
    {
        // Create a feed with a pornstar
        $feed = Feed::factory()->create();
        $pornstars = $this->createPornstarsAndRelations($feed, 1);

        $this->get(route('pornstars.show', ['feed_id' => $feed->id, 'pornstar_id' => $pornstars[0]->id]))
            ->assertStatus(200)
            ->assertViewIs('pornstars.show')
            ->assertViewHas('pornstar')
            ->assertViewHas('feed_id', $feed->id)
            ->assertViewHas('next_pornstar_id', null)
            ->assertViewHas('previous_pornstar_id', null)
            ->assertSee($pornstars[0]->name)
            ->assertSee($this->formatNumber($pornstars[0]->attributes->stats->rank))
            ->assertSee($this->formatNumber($pornstars[0]->attributes->stats->views))
            ->assertSee($this->formatNumber($pornstars[0]->attributes->stats->subscriptions));
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

    public function test_show_returns_next_and_previous_pornstar_ids_when_available()
    {
        // Create a feed with multiple pornstars
        $feed = Feed::factory()->create();
        $pornstars = $this->createPornstarsAndRelations($feed, 3);

        $this->get(route('pornstars.show', ['feed_id' => $feed->id, 'pornstar_id' => $pornstars[1]->id]))
            ->assertStatus(200)
            ->assertViewIs('pornstars.show')
            ->assertViewHas('pornstar')
            ->assertViewHas('feed_id', $feed->id)
            ->assertViewHas('next_pornstar_id', $pornstars[2]->id)
            ->assertViewHas('previous_pornstar_id', $pornstars[0]->id)
            ->assertSee($pornstars[1]->name)
            ->assertSee($this->formatNumber($pornstars[1]->attributes->stats->rank))
            ->assertSee($this->formatNumber($pornstars[1]->attributes->stats->views))
            ->assertSee($this->formatNumber($pornstars[1]->attributes->stats->subscriptions));
    }

    /**
     * @param Model|Collection $feed
     * @param int $count
     * @return Collection
     */
    public function createPornstarsAndRelations(
        \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection $feed,
        int $howManyPornstars
    ): \Illuminate\Database\Eloquent\Collection
    {
        $pornstars = Pornstar::factory()->count($howManyPornstars)->create(['feed_id' => $feed->id]);
        // create the attributes for each pornstar
        $attributes = [];
        foreach ($pornstars as $pornstar) {
            Thumbnail::factory()->count(3)->create(['pornstar_id' => $pornstar->id]);
            $attributes[] = Attribute::factory()->create(['pornstar_id' => $pornstar->id]);
        }
        foreach ($attributes as $attribute) {
            Stat::factory()->create(['attribute_id' => $attribute->id]);
        }

        return $pornstars;
    }
}
