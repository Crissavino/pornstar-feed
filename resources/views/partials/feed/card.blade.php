<div class="col-8 col-sm-6 col-md-4 col-lg-3 p-2 m-auto">
    <div class="card">
        <div class="card-body text-light" style="display: flex; flex-direction: column; justify-content: space-between; height: 250px;">
            <h6 class="card-title">
                <span>Site</span> <br>
                <span class="fw-bold">{{ $feed->site }}</span>
            </h6>
            <h6 class="card-subtitle mb-2">
                <span>Generation Date</span> <br>
                <span class="fw-bold">{{ $feed->generation_date }}</span>
            </h6>
            <div>
                <span>Items Count</span> <br>
                <span class="fw-bold">{{ $feed->items_count }}</span>
            </div>

            <a href="{{ route('pornstars.index', ['feed_id' => $feed->id]) }}"
               class="btn fw-bold porn-hub-link-button mt-2" style="background-color: var(--primary-color);">
                Porn Stars
            </a>
        </div>
    </div>
</div>
