<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\DisplayImageHelper;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|Banner active()
 */
class Banner extends Model
{
    use HasFactory;

    protected $appends = ['display_image'];

    protected $fillable = [
        'image',
        'title',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope to get only active banners
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getDisplayImageAttribute()
    {
        return DisplayImageHelper::get($this->image, 'assets/img/placeholder.png');
    }
}
