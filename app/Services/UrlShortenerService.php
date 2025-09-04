<?php

namespace App\Services;

use App\Models\Link;
use Illuminate\Support\Str;

class UrlShortenerService
{
    /**
     * Generate a unique short URL stub
     */
    public function generateStub(int $length = 6): string
    {
        do {
            $stub = $this->generateRandomStub($length);
        } while (Link::where('short_url', $stub)->exists());

        return $stub;
    }

    /**
     * Generate a random stub
     */
    private function generateRandomStub(int $length): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        return substr(str_shuffle(str_repeat($characters, ceil($length / strlen($characters)))), 0, $length);
    }

    /**
     * Check if a custom stub is available
     */
    public function isStubAvailable(string $stub): bool
    {
        // Check if stub contains only allowed characters
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $stub)) {
            return false;
        }

        // Check if stub is not too short or too long
        if (strlen($stub) < 3 || strlen($stub) > 20) {
            return false;
        }

        // Check if stub doesn't conflict with existing routes
        $reservedStubs = [
            'api', 'admin', 'dashboard', 'login', 'register', 'logout', 
            'profile', 'settings', 'links', 'categories', 'auth',
            'password', 'email', 'verification', 'app', 'www',
            'ftp', 'mail', 'pop', 'pop3', 'imap', 'smtp', 'stage',
            'staging', 'test', 'testing', 'dev', 'development',
        ];

        if (in_array(strtolower($stub), $reservedStubs)) {
            return false;
        }

        // Check if stub is already taken
        return !Link::where('short_url', $stub)->exists();
    }

    /**
     * Create a shortened URL
     */
    public function createShortUrl(string $originalUrl, ?string $customStub = null, ?int $userId = null): Link
    {
        $shortUrl = $customStub ?: $this->generateStub();

        if ($customStub && !$this->isStubAvailable($customStub)) {
            throw new \InvalidArgumentException('Custom stub is not available');
        }

        // Get default category and link type, create if they don't exist
        $defaultCategory = null;
        $defaultLinkType = null;

        if ($userId) {
            // For authenticated users, create/find a "Shortened URLs" category
            $defaultCategory = \App\Models\Category::firstOrCreate([
                'name' => 'Shortened URLs',
                'user_id' => $userId,
            ], [
                'description' => 'URLs created via the shortening service',
                'color' => '#6366f1',
                'icon' => 'link',
                'is_default' => false
            ]);
        } else {
            // For anonymous users, find or create a system category
            $defaultCategory = \App\Models\Category::firstOrCreate([
                'name' => 'Anonymous Short Links',
                'user_id' => null,
            ], [
                'description' => 'Short links created by anonymous users',
                'color' => '#6366f1',
                'icon' => 'link',
                'is_default' => false
            ]);
        }

        $defaultLinkType = \App\Models\LinkType::firstOrCreate([
            'name' => 'Short Link'
        ], [
            'description' => 'Shortened URL links',
            'icon' => 'link',
            'sort_order' => 100
        ]);

        $link = Link::create([
            'user_id' => $userId,
            'category_id' => $defaultCategory->id,
            'link_type_id' => $defaultLinkType->id,
            'title' => $this->extractTitleFromUrl($originalUrl),
            'url' => $originalUrl,
            'short_url' => $shortUrl,
            'is_public' => true, // Short URLs are public by default
            'description' => 'Shortened URL',
        ]);

        return $link;
    }

    /**
     * Extract title from URL
     */
    private function extractTitleFromUrl(string $url): string
    {
        $domain = parse_url($url, PHP_URL_HOST);
        return $domain ? "Link from " . $domain : "Shortened URL";
    }

    /**
     * Get the full short URL
     */
    public function getFullShortUrl(string $stub): string
    {
        return url('/' . $stub);
    }
}
