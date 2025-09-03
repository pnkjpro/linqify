<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'link_type_id',
        'title',
        'url',
        'description',
        'tags',
        'is_favorite',
        'is_public',
        'click_count',
        'meta_title',
        'meta_description',
        'meta_image',
        'last_accessed_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_favorite' => 'boolean',
        'is_public' => 'boolean',
        'click_count' => 'integer',
        'last_accessed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the link.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the link.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the link type that owns the link.
     */
    public function linkType(): BelongsTo
    {
        return $this->belongsTo(LinkType::class);
    }

    /**
     * Increment the click count and update last accessed time.
     */
    public function incrementClickCount(): void
    {
        $this->increment('click_count');
        $this->update(['last_accessed_at' => Carbon::now()]);
    }

    /**
     * Get the domain from the URL.
     */
    public function getDomainAttribute(): string
    {
        return parse_url($this->url, PHP_URL_HOST) ?? '';
    }

    /**
     * Get the favicon URL for the link.
     */
    public function getFaviconUrlAttribute(): string
    {
        $domain = $this->domain;
        return $domain ? "https://www.google.com/s2/favicons?domain={$domain}" : '';
    }

    /**
     * Scope a query to only include links for a specific user.
     */
    public function scopeForUser(Builder $query, $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include favorite links.
     */
    public function scopeFavorites(Builder $query): Builder
    {
        return $query->where('is_favorite', true);
    }

    /**
     * Scope a query to only include public links.
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeInCategory(Builder $query, $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope a query to filter by link type.
     */
    public function scopeOfType(Builder $query, $linkTypeId): Builder
    {
        return $query->where('link_type_id', $linkTypeId);
    }

    /**
     * Scope a query to search links.
     */
    public function scopeSearch(Builder $query, $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('url', 'like', "%{$search}%")
              ->orWhereJsonContains('tags', $search);
        });
    }

    /**
     * Scope a query to filter by tags.
     */
    public function scopeWithTags(Builder $query, array $tags): Builder
    {
        foreach ($tags as $tag) {
            $query->whereJsonContains('tags', $tag);
        }
        return $query;
    }

    /**
     * Scope a query to order by most popular (click count).
     */
    public function scopePopular(Builder $query): Builder
    {
        return $query->orderBy('click_count', 'desc');
    }

    /**
     * Scope a query to order by most recent.
     */
    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to order by recently accessed.
     */
    public function scopeRecentlyAccessed(Builder $query): Builder
    {
        return $query->orderBy('last_accessed_at', 'desc');
    }
}
