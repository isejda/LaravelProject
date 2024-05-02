<?php

namespace App;

use App\Mail\NewUserWelcomeEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','lastname', 'email', 'username','birthday', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function ($user){
            $user ->profile()->create([
                'title' => '',
            ]);

            Mail::to($user->email)->send(new NewUserWelcomeEmail());
        });
    }

    //plural because one user can have many posts
    public function posts()
    {
        return $this->hasMany(Post::class)->orderBy('created_at', 'DESC');
    }
    public function following()
    {
        return $this->belongsToMany(Profile::class);
    }

    //singular because one user can only have one profile
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
}
