<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\DisplayImageHelper;


class Setting extends Model
{
    use HasFactory;
    protected $appends = ['display_image'];
    protected $fillable = [
        'key',
        'value',
    ];

     public function getDisplayImageAttribute()
    {
        return DisplayImageHelper::get($this->value, 'assets/img/placeholder.png'); 
    }
}
