<?php

namespace App\Models;

use App\Helpers\DisplayImageHelper;
use Illuminate\Database\Eloquent\Model;




class MenuItem extends Model
{
    // 
    protected  $appends = ['display_image'];
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
     public function getDisplayImageAttribute()
    {
        return DisplayImageHelper::get($this->image);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    
}
