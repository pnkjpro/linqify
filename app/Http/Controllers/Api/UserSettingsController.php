<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserSettingsController extends Controller
{
    /**
     * Display the user's settings.
     */
    public function show(): JsonResponse
    {
        $settings = auth()->user()->settings;
        
        if (!$settings) {
            // Create default settings if they don't exist
            $settings = UserSettings::createDefaultForUser(auth()->user());
        }

        return response()->json($settings);
    }

    /**
     * Update the user's settings.
     */
    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'theme' => 'sometimes|in:light,dark,system',
            'default_view' => 'sometimes|in:grid,list',
            'links_per_page' => 'sometimes|integer|min:10|max:100',
            'show_descriptions' => 'sometimes|boolean',
            'show_favicons' => 'sometimes|boolean',
            'auto_generate_thumbnails' => 'sometimes|boolean',
            'default_link_privacy' => 'sometimes|in:private,public',
            'dashboard_widgets' => 'sometimes|array',
            'dashboard_widgets.*' => 'string|in:recent_links,favorite_links,link_statistics,category_overview,popular_links,recent_activity',
            'notification_preferences' => 'sometimes|array',
            'notification_preferences.email_weekly_summary' => 'boolean',
            'notification_preferences.email_link_recommendations' => 'boolean',
            'notification_preferences.browser_new_link_notification' => 'boolean',
            'notification_preferences.browser_import_complete' => 'boolean',
        ]);

        $settings = auth()->user()->settings;
        
        if (!$settings) {
            // Create settings with default values merged with validated data
            $defaultData = [
                'user_id' => auth()->id(),
                'theme' => 'light',
                'default_view' => 'grid',
                'links_per_page' => 20,
                'show_descriptions' => true,
                'show_favicons' => true,
                'auto_generate_thumbnails' => true,
                'default_link_privacy' => 'private',
                'dashboard_widgets' => UserSettings::getDefaultWidgets(),
                'notification_preferences' => UserSettings::getDefaultNotificationPreferences(),
            ];
            
            $settings = UserSettings::create(array_merge($defaultData, $validated));
        } else {
            $settings->update($validated);
        }

        return response()->json($settings->fresh());
    }

    /**
     * Upload user avatar.
     */
    public function uploadAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();
        $settings = $user->settings ?? UserSettings::createDefaultForUser($user);

        // Delete old avatar if exists
        if ($settings->avatar && \Storage::exists($settings->avatar)) {
            \Storage::delete($settings->avatar);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        $settings->update(['avatar' => $path]);

        return response()->json([
            'avatar' => $settings->avatar,
            'avatar_url' => \Storage::url($settings->avatar),
            'message' => 'Avatar updated successfully'
        ]);
    }

    /**
     * Delete user avatar.
     */
    public function deleteAvatar(): JsonResponse
    {
        $settings = auth()->user()->settings;
        
        if ($settings && $settings->avatar) {
            // Delete file
            if (\Storage::exists($settings->avatar)) {
                \Storage::delete($settings->avatar);
            }
            
            // Update settings
            $settings->update(['avatar' => null]);
            
            return response()->json(['message' => 'Avatar deleted successfully']);
        }

        return response()->json(['message' => 'No avatar to delete'], 404);
    }
}
