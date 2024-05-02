@extends('layouts.app')
<style>

    .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
    }

    .position-ref {
        position: relative;
    }

    .top-right {
        position: absolute;
        right: 10px;
        top: 18px;
    }
</style>

@section('content')
<div class="container">

    <div class="flex-center position-ref full-height">
        <div class="top-right links">
            <a href="{{ url('/profile/' . auth()->user()->id) }}" class="font-weight-bold">Home</a>
        </div>
    </div>

@foreach($posts as $post)
        <div class="row">
            <div class="col-6 offset-3">
                <a href="/profile/{{$post->user->id}}">
                    <img src="/storage/{{ $post -> image }}" alt="" class="w-100">
                </a>
            </div>
        </div>


        <div class="row pt-3 pb-4">
            <div class="col-6 offset-3">
                <div>
                    <p><span class="font-weight-bold">
                        <a href="/profile/{{$post -> user->id}}">
                            <span class="text-dark">{{$post -> user->username}}</span>
                        </a>
                    </span> {{$post -> caption}}
                    </p>
                </div>
            </div>
        </div>

@endforeach

    <div class="row">
        <div class="col-12 d-flex justify-content-center">
            {{$posts->links()}}
        </div>
    </div>
</div>
@endsection
