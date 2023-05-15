@if(isset($pornstar->Thumbnails[0]) && Illuminate\Support\Facades\Cache::has('thumbnail_' . md5($pornstar->Thumbnails[0]->url)))
    @php
        // get the thumbnails for the device
        $thumbnail = $pornstar->Thumbnails()->where('type', $device)->first();
        if (!$thumbnail) {
            $thumbnail = $pornstar->Thumbnails[0];
        }
        $cacheKey = 'thumbnail_' . md5($thumbnail->url);
        $cachedImage = Illuminate\Support\Facades\Cache::get($cacheKey);
    @endphp

    <img
        src="data:image/jpeg;base64,{{ base64_encode($cachedImage) }}"
        width="{{ $pornstar->Thumbnails[0]->width }}"
        height="{{ $pornstar->Thumbnails[0]->height }}"
        class="{{ $img_class }}"
        alt="{{ $pornstar->name }}"
    >
@else
    <img
        src="{{ isset($pornstar->Thumbnails[0]) ? $pornstar->Thumbnails[0]->url : asset('img/not_found.png') }}"
        height="{{ isset($pornstar->Thumbnails[0]) ? $pornstar->Thumbnails[0]->height : '344' }}"
        class="{{ $img_class }}"
        alt="{{ $pornstar->name }}"
    >
@endif
