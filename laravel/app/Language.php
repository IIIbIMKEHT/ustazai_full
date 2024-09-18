<?php

namespace App;

use Illuminate\Support\Facades\App;

trait Language
{
    public function getTitleAttribute()
    {
        return $this["title_" . App::getLocale()];
    }
    public function getDescriptionAttribute()
    {
        return $this["description_" . App::getLocale()];
    }
}
