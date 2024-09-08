<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialType extends Model
{
    protected $table = 'types';
    use HasFactory;
    protected $fillable = [
        'title_kk',
        'title_ru',
        'description_kk',
        'description_ru',
        'image'
    ];
}
