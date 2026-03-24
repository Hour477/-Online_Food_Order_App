<?php

namespace App\Models;

use App\Helpers\DisplayImageHelper;
use App\Traits\Likeable;
use Illuminate\Database\Eloquent\Model;




    /**
 * @property int $likes_count
 */
class MenuItem extends Model
{
    use Likeable;
    // 
    protected  $appends = ['display_image', 'likes_count'];
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
        return DisplayImageHelper::get($this->image, 'assets/img/placeholder.png');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    
}
