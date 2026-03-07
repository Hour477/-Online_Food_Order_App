<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    public function menu_items(){
        
        return $this->hasMany(MenuItem::class);

    }
}
