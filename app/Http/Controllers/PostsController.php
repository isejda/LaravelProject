<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //marrim te gjithe userat qe ndiqen nga useri i loguar
        $users = auth()->user()->following()->pluck('profiles.user_id');

        /*postimet e userave qe jane ne listen e mesiperme*/
        $posts = Post::whereIn('user_id', $users)->latest()->paginate(5);

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store()
    {
        //nqs do te kishim nje field qe nuk kerkon validation dhe nuk e vendosim ne array e meposhtem
        // ajo fushe do te shmangej. ne kete rast asaj i vendosim thjesht nje empty string
        $data = request()->validate([
//            'another' => '',
            'caption' => 'required',
            'image' => 'required|image',
//            'image' => ['required','image'],
        ]);


//        $post = new \App\Post();
//        $post -> caption = $data['caption'];
//        $data->save();

//        \App\Post::create([
//            'caption' => $data['caption'],
//            'image' => $data['image'],
//        ]);


//        \App\Post::create($data);
//        auth()->user()->posts()->create($data);

        $imagePath = request('image')->store('uploads', 'public');
        $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200,1200);
        $image->save();

        auth()->user()->posts()->create([
            'caption' => $data['caption'],
            'image' => $imagePath,
        ]);



//        dd(request()->all());

        return redirect('/profile/' . auth()->user()->id);
    }

    public function show(\App\Post $post)
    {
//        return view('posts.show', [
//            'post' => $post,
//        ]);
        return view('posts.show', compact('post'));
    }
}
