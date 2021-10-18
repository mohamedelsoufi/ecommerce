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

    protected $hidden = [
        'id',
        'imageable_id',
        'imageable_type',
        'created_at',
        'updated_at'
    ];

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
