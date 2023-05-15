@extends('layouts.app')

@section('content')
    <div class="my-4 mx-auto col-12 text-center">
        <h1 class="mb-0 fw-bold">Feeds</h1>

       <div class="row">
           @foreach ($feeds as $feed)
               @include('partials.feed.card', ['feed' => $feed])
           @endforeach
       </div>
    </div>
@endsection
