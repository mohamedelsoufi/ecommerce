<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $table = 'users';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id'                => 'integer',
        'email_verified_at' => 'datetime',
        'status'            => 'integer',
        'gender'            => 'integer',
    ];
    //relations
    public function Comments(){
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function Favourites(){
        return $this->hasMany(Favourit::class, 'user_id');
    }

    public function Loves(){
        return $this->hasMany(Love::class, 'user_id');
    }

    public function Ratings(){
        return $this->hasMany(Rating::class, 'user_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }
    /////
    public function getGender()
    {
        return $this->gender == 0 ? 'male': 'famale';
    }

    public function getStatus()
    {
        return $this->status == 1 ? 'active': 'blocked';
    }

    public function getChangStatus()
    {
        return $this->status == 1 ? 'blocked': 'active';
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
