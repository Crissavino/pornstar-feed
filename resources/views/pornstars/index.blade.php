@extends('layouts.app')

@section('content')
    <div class="my-4 mx-auto col-12 text-center">
        <div class="mb-5 d-flex justify-content-between align-items-center">
            <h1 class="mb-0 fw-bold">Porn Stars</h1>
            <a href="{{ route('feeds.index') }}" class="btn fw-bold" style="background-color: var(--primary-color)">Go Back</a>
        </div>
        <div class="row">
            @if(!count($pornstars))
                <h2>No pornstars found.</h2>
            @else
                @foreach ($pornstars as $pornstar)
                    @include('partials.pornstar.card', ['pornstar' => $pornstar, 'device' => $device])
                @endforeach

                <div class="d-flex mt-2 justify-content-center">
                    {!! $pornstars->links() !!}
                </div>
            @endif
        </div>
    </div>
@endsection
