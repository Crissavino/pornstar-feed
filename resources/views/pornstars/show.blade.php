@extends('layouts.app')

@section('content')
    <div class="change-pornstar-arrows">
        @if($previous_pornstar_id)
            <a class="arrow-left" href="{{ route('pornstars.show', ['feed_id' => $feed_id, 'pornstar_id' => $previous_pornstar_id]) }}">
                <span></span>
            </a>
        @endif
        @if($next_pornstar_id)
            <a class="arrow-right" href="{{ route('pornstars.show', ['feed_id' => $feed_id, 'pornstar_id' => $next_pornstar_id]) }}">
                <span></span>
            </a>
        @endif
    </div>

    <div class="pornstar mt-5">
        <a href="{{ route('pornstars.index', ['feed_id' => $feed_id]) }}" class="btn fw-bold" style="background-color: var(--primary-color); position: absolute; right: 20px">Go Back</a>

        <div class="pornstar-header">

            <div class="pornstar-img-name">
                @include('partials.thumbnail', ['pornstar' => $pornstar, 'device' => $device], ['img_class' => ''])
                <div class="pornstar-name">
                    <h1>{{ ucwords($pornstar->name) }}</h1>
                    @include('partials.license_check', ['pornstar' => $pornstar])
                </div>
            </div>

            <div class="pornstar-header-info">
                @include('partials.pornstar.header_info_item', ['value' => $pornstar->attributes->stats->rank, 'label' => 'Rank'])
                @include('partials.pornstar.header_info_item', ['value' => $pornstar->attributes->stats->views, 'label' => 'Videos Views'])
                @include('partials.pornstar.header_info_item', ['value' => $pornstar->attributes->stats->subscriptions, 'label' => 'Subscribers'])
            </div>

        </div>

        <div class="pornstar-body mt-5 p-5">
            <div class="row">
                <div class="col-12 col-lg-6">
                    @include('partials.pornstar.body_info_item', ['value' => $pornstar->attributes->stats->monthly_searches, 'label' => 'Monthly Searches:'])
                    @include('partials.pornstar.body_info_item', ['value' => $pornstar->attributes->stats->videos_count, 'label' => 'Videos:'])
                    @include('partials.pornstar.body_info_item', ['value' => $pornstar->attributes->stats->premium_videos_count, 'label' => 'Premium Videos:'])
                    @include('partials.pornstar.body_info_item', ['value' => $pornstar->attributes->stats->white_label_video_count, 'label' => 'White Label Videos:'])
                    @include('partials.pornstar.body_info_item', ['value' => ucfirst($pornstar->attributes->hair_color), 'label' => 'Hair Color:'])
                    @include('partials.pornstar.body_info_item', ['value' => ucfirst($pornstar->attributes->ethnicity), 'label' => 'Ethnicity:'])
                </div>
                <div class="col-12 col-lg-6">
                    @include('partials.pornstar.body_info_item', ['value' => $pornstar->attributes->tattoos ? 'Yes' : 'No', 'label' => 'Tattoos:'])
                    @include('partials.pornstar.body_info_item', ['value' => $pornstar->attributes->piercings ? 'Yes' : 'No', 'label' => 'Piercings:'])
                    @include('partials.pornstar.body_info_item', ['value' => $pornstar->attributes->breast_size, 'label' => 'Breast Size:'])
                    @include('partials.pornstar.body_info_item', ['value' => ucfirst($pornstar->attributes->gender), 'label' => 'Gender:'])
                    @include('partials.pornstar.body_info_item', ['value' => ucfirst($pornstar->attributes->orientation), 'label' => 'Orientation:'])
                    @include('partials.pornstar.body_info_item', ['value' => $pornstar->attributes->age, 'label' => 'Age:'])
                </div>
            </div>
        </div>

    </div>
@endsection
