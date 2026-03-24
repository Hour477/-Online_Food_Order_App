<?php

namespace App\Traits;

use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Cache;

trait Likeable
{
    /**
     * Get all of the model's likes.
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Like the model by the given user.
     */
    public function like(User $user): void
    {
        if (!$this->isLikedBy($user)) {
            $this->likes()->create(['user_id' => $user->id]);
            $this->incrementLikesCount();
        }
    }

    /**
     * Unlike the model by the given user.
     */
    public function unlike(User $user): void
    {
        $this->likes()->where('user_id', $user->id)->delete();
        $this->decrementLikesCount();
    }

    /**
     * Check if the model is liked by the given user.
     */
    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Get the total likes count for the model.
     * Uses Redis caching for performance.
     */
    public function getLikesCountAttribute(): int
    {
        $cacheKey = $this->getLikesCountCacheKey();

        return Cache::remember($cacheKey, now()->addDay(), function () {
            return $this->likes()->count();
        });
    }

    /**
     * Increment the likes count in cache.
     */
    protected function incrementLikesCount(): void
    {
        $cacheKey = $this->getLikesCountCacheKey();
        if (Cache::has($cacheKey)) {
            Cache::increment($cacheKey);
        } else {
            $this->getLikesCountAttribute(); // Initialize cache
        }
    }

    /**
     * Decrement the likes count in cache.
     */
    protected function decrementLikesCount(): void
    {
        $cacheKey = $this->getLikesCountCacheKey();
        if (Cache::has($cacheKey)) {
            Cache::decrement($cacheKey);
        } else {
            $this->getLikesCountAttribute(); // Initialize cache
        }
    }

    /**
     * Get the cache key for likes count.
     */
    protected function getLikesCountCacheKey(): string
    {
        return "likes_count:{$this->getMorphClass()}:{$this->id}";
    }
}
