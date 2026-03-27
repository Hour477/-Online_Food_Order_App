<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

// use App\Traits\HasDisplayImage;
use App\Helpers\DisplayImageHelper;


use App\Models\Like;
use App\Models\MenuItem;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;
    protected  $appends = ['display_image'];

    /**
     * Get all of the products that the user has liked.
     */
    public function likedMenuItems(): MorphToMany
    {
        return $this->morphedByMany(MenuItem::class, 'likeable', 'likes')
                    ->withTimestamps();
    }

    /**
     * Check if the user has liked the given model.
     */
    public function hasLiked(MenuItem $model): bool
    {
        return $this->likedMenuItems()->where('likeable_id', $model->id)->exists();
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'image',
        'phone',
        'state',
        'address',
        'city',
    ];

     public function getDisplayImageAttribute()
    {
        return DisplayImageHelper::get($this->image, 'assets/img/placeholder.png');
    }
    // relationship to user to role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');  // ← explicit foreign key
        
        
    }

    /**
     * Get the orders for the user.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    
}
