<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CacheThumbnailImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $thumbnailsUrls;

    public int $tries = 3; // Set the maximum number of retries

    /**
     * Create a new job instance.
     *
     * @param array $thumbnailsUrls
     */
    public function __construct(array $thumbnailsUrls)
    {
        $this->thumbnailsUrls = $thumbnailsUrls;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {

        foreach ($this->thumbnailsUrls as $thumbnailUrl) {
            $this->cacheThumbnail($thumbnailUrl);
        }
    }

    /**
     * Check if the exception was caused by a deadlock.
     *
     * @param  \Illuminate\Database\QueryException  $e
     * @return bool
     */
    protected function causedByDeadlock(\Illuminate\Database\QueryException $e): bool
    {
        $errorCodes = [
            '40001', // MySQL
            '40P01', // PostgreSQL
            'HYT00', // Microsoft SQL Server
        ];

        return in_array($e->getCode(), $errorCodes);
    }

    private function cacheThumbnail(string $thumbnailUrl): void
    {
        $cacheKey = 'thumbnail_' . md5($thumbnailUrl);

        if (!Cache::has($cacheKey)) {
            try {
                $response = Http::withOptions(['timeout' => 15])->get($thumbnailUrl);

                if ($response->successful()) {
                    Log::info('Cache key: ' . $cacheKey);
                    $imageContents = $response->body();
                    // 1 day = 86400 seconds
                    Cache::put($cacheKey, $imageContents, 86400);
                }
            } catch (\Illuminate\Database\QueryException $e) {
                // Retry the job if the error code indicates a deadlock
                if ($this->causedByDeadlock($e) && $this->attempts() < $this->tries) {
                    $this->release(10); // Delay the retry for 10 seconds
                }
            } catch (\Exception $e) {
                // Log::error('Failed to cache thumbnail image: ' . $e->getMessage());
            }
        }
    }
}
