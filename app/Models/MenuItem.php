<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MenuItem extends Model
{
    //
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'status',
        'created_at',
        'updated_at'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    
}
