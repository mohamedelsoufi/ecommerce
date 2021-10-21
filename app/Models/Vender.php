<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Vender extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    protected $table = 'venders';

    protected $guarded = [];

    protected $casts = [
        'id'                => 'integer',
        'email_verified_at' => 'datetime',
        'status'            => 'integer',
        'gender'            => 'integer',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    //relations
    public function Products(){
        return $this->hasMany(Product::class, 'vender_id');
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
