<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Article;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relationships
     */

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Local Attributes
     */

     /**
     * Check if the user email is verified
     */
    public function isVerified(): bool
    {
        return filled($this->email_verified_at);
    }

    /**
     * Check if user is mine
     */
    public function isMine(): bool
    {
        return auth()->id() === $this->id;
    }

    /**
     * Check if the user has password
     */
    public function hasPassword(): bool
    {
        return filled($this->password);
    }

    /**
     * Name
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Local Scopes
     */

    /**
     * Query user by username
     */
    public function scopeHasUsername(Builder $query, string $username): void
    {
        $query->where('email', $username);
    }
}
