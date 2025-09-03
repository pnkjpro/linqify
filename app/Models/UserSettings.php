<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'theme',
        'default_view',
        'links_per_page',
        'show_descriptions',
        'show_favicons',
        'auto_generate_thumbnails',
        'default_link_privacy',
        'dashboard_widgets',
        'notification_preferences',
        'avatar',
    ];

    protected $casts = [
        'links_per_page' => 'integer',
        'show_descriptions' => 'boolean',
        'show_favicons' => 'boolean',
        'auto_generate_thumbnails' => 'boolean',
        'dashboard_widgets' => 'array',
        'notification_preferences' => 'array',
    ];

    /**
     * Get the user that owns the settings.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the default dashboard widgets.
     */
    public static function getDefaultWidgets(): array
    {
        return [
            'recent_links',
            'favorite_links',
            'link_statistics',
            'category_overview'
        ];
    }

    /**
     * Get the default notification preferences.
     */
    public static function getDefaultNotificationPreferences(): array
    {
        return [
            'email_weekly_summary' => true,
            'email_link_recommendations' => false,
            'browser_new_link_notification' => true,
            'browser_import_complete' => true,
        ];
    }

    /**
     * Create default settings for a user.
     */
    public static function createDefaultForUser(User $user): self
    {
        return self::create([
            'user_id' => $user->id,
            'theme' => 'light',
            'default_view' => 'grid',
            'links_per_page' => 20,
            'show_descriptions' => true,
            'show_favicons' => true,
            'auto_generate_thumbnails' => true,
            'default_link_privacy' => 'private',
            'dashboard_widgets' => self::getDefaultWidgets(),
            'notification_preferences' => self::getDefaultNotificationPreferences(),
        ]);
    }
}
