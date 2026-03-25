<?php

namespace App\Models;

use App\Helpers\DisplayImageHelper;
use App\Traits\Likeable;
use Illuminate\Database\Eloquent\Model;




    /**
 * @property int $likes_count
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem search(?string $search)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem inCategory(array $categoryIds)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem inPriceRange($min, $max)
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem popularLike()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem topRated()
 * @method static \Illuminate\Database\Eloquent\Builder|MenuItem topDishes()
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
        'rating',
        'popularity',
        'image',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * Scope: Search by name or description
     */
    public function scopeSearch($query, $search)
    {
        if (!$search) return $query;
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('description', 'like', "%$search%");
        });
    }

    /**
     * Scope: Filter by multiple categories
     */
    public function scopeInCategory($query, $categoryIds)
    {
        if (empty($categoryIds)) return $query;
        return $query->whereIn('category_id', $categoryIds);
    }

    /**
     * Scope: Filter by price range
     */
    public function scopeInPriceRange($query, $min, $max)
    {
        if ($min !== null) $query->where('price', '>=', $min);
        if ($max !== null) $query->where('price', '<=', $max);
        return $query;
    }

    /**
     * Scope: Popular Like (items with likes)
     */
    public function scopePopularLike($query)
    {
        return $query->withCount('likes')->orderBy('likes_count', 'desc');
    }

    /**
     * Scope: Top Rated (rating >= 4.5)
     */
    public function scopeTopRated($query)
    {
        return $query->where('rating', '>=', 4.5);
    }

    /**
     * Scope: Top Dishes (popularity or based on order count)
     */
    public function scopeTopDishes($query)
    {
        return $query->orderBy('popularity', 'desc');
    }

    public function getDisplayImageAttribute()
    {
        return DisplayImageHelper::get($this->image, 'assets/img/placeholder.png');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    
}
