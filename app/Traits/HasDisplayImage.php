<?php

namespace App\Traits;

use App\Helpers\DisplayImageHelper;

trait HasDisplayImage
{
    public function getDisplayImageAttribute()
    {
        return DisplayImageHelper::get($this->image);
    }
}