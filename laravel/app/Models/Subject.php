<?php

namespace App\Models;

use App\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    use Language;

    protected $fillable = ['title_kk', 'title_ru'];

    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
