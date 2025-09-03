<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

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

    /**
     * Get the links for the user.
     */
    public function links()
    {
        return $this->hasMany(Link::class);
    }

    /**
     * Get the categories for the user.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Get the user's settings.
     */
    public function settings()
    {
        return $this->hasOne(UserSettings::class);
    }

    /**
     * Get or create user settings.
     */
    public function getOrCreateSettings()
    {
        return $this->settings()->firstOrCreate(
            ['user_id' => $this->id],
            [
                'theme' => 'light',
                'default_view' => 'grid',
                'links_per_page' => 20,
                'show_descriptions' => true,
                'show_favicons' => true,
                'auto_generate_thumbnails' => true,
                'default_link_privacy' => 'private',
                'dashboard_widgets' => UserSettings::getDefaultWidgets(),
                'notification_preferences' => UserSettings::getDefaultNotificationPreferences(),
            ]
        );
    }

    /**
     * Get the user's favorite links.
     */
    public function favoriteLinks()
    {
        return $this->links()->where('is_favorite', true);
    }

    /**
     * Get the user's public links.
     */
    public function publicLinks()
    {
        return $this->links()->where('is_public', true);
    }
}
