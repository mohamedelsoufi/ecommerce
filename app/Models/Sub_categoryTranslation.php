<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sub_categoryTranslation extends Model
{
    use HasFactory;
    protected $table = 'sub_categories_translations';

    protected $guarded = [];
    public $timestamps = false;
}
