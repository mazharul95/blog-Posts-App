@extends('layouts.app')

@section('title', 'Blog Posts')

@section('content')
    <div class="row">
        <div class="col-8">
            {{-- @each('posts.partials.post', $posts, 'post') --}}
            @forelse ($posts as $key => $post)
                @include('posts.partials.post', [])
            @empty
                <p>No blog Posts yet!</p>
            @endforelse
        </div>
        <div class="col-4">
            <div class="container">
                <div class="row">

                    @card(['title' => 'Most Commented'])
                        @slot('subtitle')
                            What people are currently talking about
                        @endslot

                        @slot('items')
                            @foreach ($mostCommented as $post)
                                <li class="list-group-item">
                                    <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                                        {{ $post->title }}
                                    </a>
                                </li>
                            @endforeach
                        @endslot
                    @endcard
                </div>

                <div class="row mt-4">
                    @card(['title' => 'Most Active'])
                        @slot('subtitle')
                            User with most posts written
                        @endslot
                        @slot('items', collect($mostActive)->pluck('name'))
                    @endcard

                </div>

                <div class="row mt-4">
                    @card(['title' => 'Most Active Last Month'])
                        @slot('subtitle')
                            User with most posts written in the last month
                        @endslot
                        @slot('items', collect($mostActiveLastMonth)->pluck('name'))
                    @endcard


                </div>
            </div>
        </div>
    </div>
@endsection
