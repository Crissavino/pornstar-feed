<div class="col-8 col-sm-6 col-md-4 col-lg-3 p-2 m-auto">
    <div class="card">
        @include('partials.thumbnail', ['pornstar' => $pornstar, 'device' => $device], ['img_class' => 'card-img-top'])
        <div class="card-body d-flex flex-column align-items-center">
            <div class="d-flex mb-3">
                <h5 class="card-title text-light m-0 me-3 fw-bold">{{ ucwords($pornstar->name) }}</h5>
                @include('partials.license_check', ['pornstar' => $pornstar])
            </div>
            @php
                $alises = json_decode($pornstar->aliases);
            @endphp
            <div>
                @if(count($alises) > 0)
                    @foreach ($alises as $alias)
                        <span class="badge rounded-pill text-bg-warning">{{ $alias }}</span>
                    @endforeach
                @endif
            </div>
            <div class="mt-3">
                <a href="{{ $pornstar->link }}" class="btn fw-bold porn-hub-link-button" style="background-color: var(--primary-color);">Porn Hub Link</a>
                <a href="{{ route('pornstars.show', ['feed_id' => $feed_id, 'pornstar_id' => $pornstar->id]) }}" class="btn btn-light">See Details</a>
            </div>
        </div>
    </div>
</div>
