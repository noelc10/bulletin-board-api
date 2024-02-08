<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ArticleUpvote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'article_id',
        'user_id'
    ];

    /**
     * Relationships
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class);
    }

    /**
     * Local Attributes
     */

    /**
     * Get total upvotes
     *
     * @return integer
     */
    public function getTotalUpvotesAttribute(): int
    {
        return $this->count();
    }

    /**
     * Check if authenticated user is upvoted
     *
     * @return boolean
     */
    public function scopeIsAuthUserUpvoted(): bool
    {
        return $this->where('user_id', auth()->user()->id)->exists();
    }
}
