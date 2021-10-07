<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Image extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'images';

    protected $guarded = [];

    protected $casts = [
        'id'            => 'integer',
        'imageable_id'  => 'integer',
    ];

    //relations
    public function imageable()
    {
        return $this->morphTo();
    }
}
