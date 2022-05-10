<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Vendor extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    protected $table = 'vendors';

    protected $guarded = [];

    protected $casts = [
        'id'                => 'integer',
        'verified'          => 'integer',
        'status'            => 'integer',
        'gender'            => 'integer',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    //relations
    public function Products(){
        return $this->hasMany(Product::class, 'vendor_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function Image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }
    /////
    public function getImage(){
        if($this->Image != null){
            return url('public/uploads/vendors/' . $this->Image->src);
        } else {
            return url('public/uploads/vendors/default.jpg');
        }
    }

    public function getGender()
    {
        return $this->gender == 0 ? trans('guest.male'): trans('guest.famale');
    }

    public function getStatus()
    {
        return $this->status == 1 ? trans('guest.active'): trans('guest.blocked');
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
        return [
            'type'       => 'vendor'
        ];
    }
}
