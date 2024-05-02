@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-3 p-5">
            <img src="{{$user->profile->profileImage()}}" class="rounded-circle w-100">
        </div>
        <div class="col-9 pt-5">
            <div class="d-flex justify-content-between align-items-baseline">
                <div class="d-flex align-items-center pb-3">
                    <div class="h4 font-weight-normal">{{ $user -> username }}</div>

                    @cannot('update', $user->profile)
                    <follow-button user-id="{{ $user->id }}" follows="{{ $follows }}"></follow-button>
                    @endcannot

                </div>
                @can('update', $user->profile)
                    <a href="/p/create" class="font-weight-bold">Add new post</a>
                @endcan
            </div>

            @can('update', $user->profile)
            <a href="/profile/{{$user -> id}}/edit" class="font-weight-bold">Edit profile</a>
            @endcan

{{--            @auth--}}
{{--                <a href="/profile/{{$user -> id}}/edit" class="font-weight-bold">Edit profile</a>--}}
{{--            @endauth--}}

            <div class="d-flex">
                <div class="pr-5"><strong>{{$postsCount}}</strong> posts</div>
                <div class="pr-5"><strong>{{$followersCount}}</strong> followers</div>
                <div class="pr-5"><strong>{{$followingCount}}</strong> following</div>
            </div>
            <div class="pt-4 font-weight-bold">{{ $user->profile->title }}</div>
            <div>{{ $user->profile->description }}</div>
            <div class="font-weight-bold"><a href="{{ $user->profile->url ?? '#' }}">{{ $user->profile->url ?? 'N/A' }}</a></div>
        </div>
    </div>

    <div class="row pt-5">
        @foreach($user->posts as $post)
            <div class="col-4 pb-4">
                <a href="/p/{{ $post->id }}">
                    <img src="/storage/{{ $post->image }}" class="w-100">
                </a>
            </div>
        @endforeach

    </div>

</div>
@endsection
