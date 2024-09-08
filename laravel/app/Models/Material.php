<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject_id',
        'type_id',
        'title',
        'content',
        'class_level',
        'word_link'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function type()
    {
        return $this->belongsTo(MaterialType::class, 'type_id', 'id');
    }
}
