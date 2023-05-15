<?php

namespace Tests\Feature;

use App\Models\Feed;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DownloadPornstarFeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_feed_download_success()
    {
        $now = Carbon::now()->toIso8601ZuluString();
        // Mock the HTTP response
        Http::fake([
            'https://www.pornhub.com/files/json_feed_pornstars.json' => Http::response($this->fakeFeedData($now), 200),
        ]);

        // Run the command
        $this->artisan('feed:download')
            ->expectsOutput('Feed downloaded successfully')
            ->expectsOutput('Feed contains 8 items')
            ->expectsOutput('Feed generation date: ' . $now)
            ->expectsOutput('Feed site: https://www.pornhub.com')
            ->expectsOutput('Feed downloaded and data saved successfully.')
            ->expectsOutput('Caching feed images...')
            ->expectsOutput('Feed images cached successfully.')
            ->assertExitCode(0);

        // Add assertions to check the database
        $this->assertDatabaseCount('feeds', 1);
        $this->assertDatabaseCount('pornstars', 8);
        $this->assertDatabaseCount('attributes', 8);
        $this->assertDatabaseCount('stats', 8);
        // 3 thumbnails per pornstar
        $this->assertDatabaseCount('thumbnails', 24);

    }

    private function fakeFeedData(string $now): string
    {
        return '{"site":"https://www.pornhub.com","generationDate":"'. $now .'","items":[{"attributes":{"hairColor":"Blonde","ethnicity":"White","tattoos":true,"piercings":true,"breastSize":34,"breastType":"A","gender":"female","orientation":"straight","age":42,"stats":{"subscriptions":5501,"monthlySearches":674600,"views":442072,"videosCount":54,"premiumVideosCount":31,"whiteLabelVideoCount":46,"rank":4523,"rankPremium":4638,"rankWl":4369}},"id":2,"name":"Aaliyah Jolie","license":"REGULAR","wlStatus":"1","aliases":["Aliyah Julie","Aliyah Jolie","Aaliyah","Macy"],"link":"https:\/\/www.pornhub.com\/pornstar\/aaliyah-jolie","thumbnails":[{"height":344,"width":234,"type":"pc","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/002\/(m=lciuhScOb_c)(mh=5Lb6oqzf58Pdh9Wc)thumb_22561.jpg"]},{"height":344,"width":234,"type":"mobile","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/002\/(m=lciuhScOb_c)(mh=5Lb6oqzf58Pdh9Wc)thumb_22561.jpg"]},{"height":344,"width":234,"type":"tablet","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/002\/(m=lciuhScOb_c)(mh=5Lb6oqzf58Pdh9Wc)thumb_22561.jpg"]}]},{"attributes":{"hairColor":"Blonde","ethnicity":"White","tattoos":true,"piercings":false,"breastSize":34,"breastType":"C","gender":"female","orientation":"straight","age":38,"stats":{"subscriptions":6852,"monthlySearches":129900,"views":617842,"videosCount":102,"premiumVideosCount":62,"whiteLabelVideoCount":94,"rank":8738,"rankPremium":9046,"rankWl":8400}},"id":3,"name":"Aaralyn Barra","license":"REGULAR","wlStatus":"1","aliases":["Aralyn Barra","Aralynn Barra","Aarlyn Barra","Aarlynn","Aarilyn Berra","Aaralynn Barra","Arryolyn Barra","Aaryn Barra","Aaralyn. Aaralyn Berra","Aarolyn Barra"],"link":"https:\/\/www.pornhub.com\/pornstar\/aaralyn-barra","thumbnails":[{"height":344,"width":234,"type":"pc","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/003\/(m=lciuhScOb_c)(mh=LBc3OpFdh3idiCoh)thumb_101.jpg"]},{"height":344,"width":234,"type":"mobile","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/003\/(m=lciuhScOb_c)(mh=LBc3OpFdh3idiCoh)thumb_101.jpg"]},{"height":344,"width":234,"type":"tablet","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/003\/(m=lciuhScOb_c)(mh=LBc3OpFdh3idiCoh)thumb_101.jpg"]}]},{"attributes":{"hairColor":"Blonde","ethnicity":"White","tattoos":false,"piercings":true,"breastSize":34,"breastType":"E","gender":"female","orientation":"straight","age":40,"stats":{"subscriptions":34727,"monthlySearches":2503600,"views":3543889,"videosCount":109,"premiumVideosCount":27,"whiteLabelVideoCount":41,"rank":2364,"rankPremium":2396,"rankWl":2299}},"id":5,"name":"Abbey Brooks","license":"REGULAR","wlStatus":"1","aliases":["abbey brook","abbey brookes","abbey brookess","abby brooklly browning","abby brooks"],"link":"https:\/\/www.pornhub.com\/pornstar\/abbey-brooks","thumbnails":[{"height":344,"width":234,"type":"pc","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/005\/(m=lciuhScOb_c)(mh=9RS1olxHY02DLVy-)thumb_548482.jpg"]},{"height":344,"width":234,"type":"mobile","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/005\/(m=lciuhScOb_c)(mh=9RS1olxHY02DLVy-)thumb_548482.jpg"]},{"height":344,"width":234,"type":"tablet","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/005\/(m=lciuhScOb_c)(mh=9RS1olxHY02DLVy-)thumb_548482.jpg"]}]},{"attributes":{"hairColor":"Blonde","ethnicity":"White","tattoos":true,"piercings":false,"breastSize":36,"breastType":"E","gender":"female","orientation":"straight","age":44,"stats":{"subscriptions":8257,"monthlySearches":460000,"views":773117,"videosCount":42,"premiumVideosCount":24,"whiteLabelVideoCount":28,"rank":5374,"rankPremium":5521,"rankWl":5188}},"id":7,"name":"Abby Rode","license":"REGULAR","wlStatus":"1","aliases":["abby rhodes","abby rhode"],"link":"https:\/\/www.pornhub.com\/pornstar\/abby-rode","thumbnails":[{"height":344,"width":234,"type":"pc","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/007\/(m=lciuhScOb_c)(mh=Wn8tiEEYE4YbJVKs)thumb_191.jpg"]},{"height":344,"width":234,"type":"mobile","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/007\/(m=lciuhScOb_c)(mh=Wn8tiEEYE4YbJVKs)thumb_191.jpg"]},{"height":344,"width":234,"type":"tablet","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/007\/(m=lciuhScOb_c)(mh=Wn8tiEEYE4YbJVKs)thumb_191.jpg"]}]},{"attributes":{"hairColor":"Brunette","ethnicity":"White","tattoos":false,"piercings":false,"breastSize":32,"breastType":"A","gender":"female","orientation":"straight","stats":{"subscriptions":1213,"monthlySearches":92800,"views":268623,"videosCount":4,"premiumVideosCount":0,"whiteLabelVideoCount":0,"rank":9697,"rankPremium":10096,"rankWl":18118}},"id":8,"name":"Abigail Clayton","license":"REGULAR","wlStatus":"0","aliases":["debbie lordan","abagail clayton","gail wezke","gail lawrence"],"link":"https:\/\/www.pornhub.com\/pornstar\/abigail-clayton","thumbnails":[{"height":344,"width":234,"type":"pc","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/008\/(m=lciuhScOb_c)(mh=ZVkT2RQJ3woIUlOp)thumb_1008292.jpg"]},{"height":344,"width":234,"type":"mobile","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/008\/(m=lciuhScOb_c)(mh=ZVkT2RQJ3woIUlOp)thumb_1008292.jpg"]},{"height":344,"width":234,"type":"tablet","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/008\/(m=lciuhScOb_c)(mh=ZVkT2RQJ3woIUlOp)thumb_1008292.jpg"]}]},{"attributes":{"hairColor":"Blonde","ethnicity":"White","tattoos":false,"piercings":true,"breastSize":36,"breastType":"D","gender":"female","orientation":"straight","age":51,"stats":{"subscriptions":428,"monthlySearches":9800,"views":156465,"videosCount":3,"premiumVideosCount":2,"whiteLabelVideoCount":3,"rank":15373,"rankPremium":16987,"rankWl":15630}},"id":10,"name":"Adajja","license":"REGULAR","wlStatus":"1","aliases":["Adaja","Adajja Adams"],"link":"https:\/\/www.pornhub.com\/pornstar\/adajja","thumbnails":[{"height":344,"width":234,"type":"pc","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/010\/(m=lciuhScOb_c)(mh=nfs9MbxeEmvUBfmm)thumb_460442.jpg"]},{"height":344,"width":234,"type":"mobile","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/010\/(m=lciuhScOb_c)(mh=nfs9MbxeEmvUBfmm)thumb_460442.jpg"]},{"height":344,"width":234,"type":"tablet","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/010\/(m=lciuhScOb_c)(mh=nfs9MbxeEmvUBfmm)thumb_460442.jpg"]}]},{"attributes":{"hairColor":"Brunette","ethnicity":"White","tattoos":true,"piercings":true,"breastSize":34,"breastType":"C","gender":"female","orientation":"straight","age":30,"stats":{"subscriptions":37908,"monthlySearches":5936700,"views":2619235,"videosCount":67,"premiumVideosCount":19,"whiteLabelVideoCount":23,"rank":1425,"rankPremium":1441,"rankWl":1387}},"id":13,"name":"Addison Lee","license":"REGULAR","wlStatus":"1","aliases":["Miss Lee"],"link":"https:\/\/www.pornhub.com\/pornstar\/addison-lee","thumbnails":[{"height":344,"width":234,"type":"pc","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/013\/(m=lciuhScOb_c)(mh=F5ol28OJNgjsr3fS)thumb_168361.jpg"]},{"height":344,"width":234,"type":"mobile","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/013\/(m=lciuhScOb_c)(mh=F5ol28OJNgjsr3fS)thumb_168361.jpg"]},{"height":344,"width":234,"type":"tablet","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/013\/(m=lciuhScOb_c)(mh=F5ol28OJNgjsr3fS)thumb_168361.jpg"]}]},{"attributes":{"hairColor":"Brunette","ethnicity":"White","tattoos":false,"piercings":true,"breastSize":32,"breastType":"B","gender":"female","orientation":"straight","age":36,"stats":{"subscriptions":5940,"monthlySearches":547900,"views":551096,"videosCount":50,"premiumVideosCount":25,"whiteLabelVideoCount":37,"rank":4960,"rankPremium":5090,"rankWl":4787}},"id":14,"name":"addison rose","license":"REGULAR","wlStatus":"1","aliases":["jenni apples"],"link":"https:\/\/www.pornhub.com\/pornstar\/addison-rose","thumbnails":[{"height":344,"width":234,"type":"pc","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/014\/(m=lciuhScOb_c)(mh=skGBO36McFk3rUrI)thumb_124331.jpg"]},{"height":344,"width":234,"type":"mobile","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/014\/(m=lciuhScOb_c)(mh=skGBO36McFk3rUrI)thumb_124331.jpg"]},{"height":344,"width":234,"type":"tablet","urls":["https:\/\/di.phncdn.com\/pics\/pornstars\/000\/000\/014\/(m=lciuhScOb_c)(mh=skGBO36McFk3rUrI)thumb_124331.jpg"]}]}], "itemsCount": 8}';
    }

    public function test_feed_already_up_to_date()
    {
        // Create a fake feed with the same generation day but 30 minutes earlier as the existing feed in the database
        Feed::factory()->create([
            'generation_date' => Carbon::now()->subMinutes(30)->toIso8601ZuluString()
        ]);

        // Mock the Http facade to simulate a successful response
        Http::fake([
            'https://www.pornhub.com/files/json_feed_pornstars.json' => Http::response(
                $this->fakeFeedData(Carbon::now()->toIso8601ZuluString()),
                200
            ),
        ]);

        // Run the command
        $this->artisan('feed:download')
            ->expectsOutput('Feed is already up to date')
            ->assertExitCode(0);

        // Assert that a new feed was not created
        $this->assertEquals(1, Feed::count());
    }

    public function test_feed_download_failed()
    {
        // Mock the Http facade to simulate a failed response
        Http::fake([
            'https://www.pornhub.com/files/json_feed_pornstars.json' => Http::response([], 500),
        ]);

        // Run the command
        $this->artisan('feed:download')
            ->expectsOutput('Failed to download the feed.')
            ->assertExitCode(1);

        // Assert that no feed was created in the database
        $this->assertEquals(0, Feed::count());
    }

}
