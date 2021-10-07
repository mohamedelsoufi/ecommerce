<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Address extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'addresses';

    protected $guarded = [];

    protected $casts = [
        'id'             => 'integer',
        'addressable_id' => 'integer'
    ];

    //relations
    public function addressable()
    {
        return $this->morphTo();
    }
}
