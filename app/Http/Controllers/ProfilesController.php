<?php

namespace App\Http\Controllers;

//meqe i bejme import kesaj ne fillim nuk eshte e nevojshme qe si parameter te funksioneve te kalojme /App/User por vetem User
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;


class ProfilesController extends Controller
{

    public function index(User $user)
    {
//        dd(User::find($user));
        //leme laravel te gjeje userin  duke vendosur si parameter tek funksioni /App/User $user
        //ne menyre manuale e bejme si me poshte
        /*$user = User::findOrFail($user);

        return view('profiles.index', [
            'user' => $user,
        ]);*/

        $follows = (auth()->user())? auth()->user()->following->contains($user->id): false;

       /* $postsCount = $user->posts->count();
        $followersCount = $user->profile ->followers->count();
        $followingCount = $user->following->count();*/

        $postsCount = Cache::remember(
            'count.posts.' . $user->id,
            now()->addSeconds(30),
            function () use ($user){
                return $user->posts->count();
            }
        );

        $followersCount = Cache::remember(
            'count.followers.' . $user->id,
            now()->addSeconds(30),
            function () use ($user){
                return $user->profile ->followers->count();
            }
        );

        $followingCount = Cache::remember(
          'count.following.' . $user->id,
          now()->addSeconds(30),
          function () use($user){
              return $user->following->count();
          }
        );


        return view('profiles.index', compact('user', 'follows', 'postsCount', 'followersCount', 'followingCount'));
    }


    public function edit(User $user)
    {
        $this->authorize('update', $user->profile);
        return view('profiles.edit', compact('user'));
    }

    public function update(User $user)
    {
        $this->authorize('update', $user->profile);

        $data = request()->validate([
            'title' => 'required' ,
            'description' => 'required',
            'url' => 'url',
            'image' => '',
        ]);

        if(request('image')){
            $imagePath = request('image')->store('profile', 'public');
            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000,1000);
            $image->save();
            $imageArray = ['image' => $imagePath];

        }

        auth()->user()->profile->update(array_merge(
            $data,
            $imageArray ?? []
        ));

        return redirect("/profile/{$user->id}");
    }
}
