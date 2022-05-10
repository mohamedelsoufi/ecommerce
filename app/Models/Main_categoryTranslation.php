<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Main_categoryTranslation extends Model
{
    use HasFactory;

    protected $table = 'main_categories_translations';

    protected $guarded = [];
    public $timestamps = false;

}
