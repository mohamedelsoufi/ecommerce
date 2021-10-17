<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Promo_code extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'promo_codes';

    protected $guarded = [];

    protected $casts = [
        'id'                => 'integer',
        'discound'          => 'integer',
    ];
}
