<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class RedirectController extends Controller
{
    /**
     * Redirect to original URL using short URL stub
     */
    public function redirect(Request $request, string $stub): RedirectResponse
    {
        $link = Link::where('short_url', $stub)->first();

        if (!$link) {
            abort(404, 'Short URL not found');
        }

        // Increment click count and update last accessed time
        $link->incrementClickCount();

        // Log the access for analytics (optional)
        $this->logAccess($request, $link);

        // Redirect to original URL
        return redirect()->away($link->url);
    }

    /**
     * Preview short URL information
     */
    public function preview(Request $request, string $stub)
    {
        $link = Link::where('short_url', $stub)->first();

        if (!$link) {
            abort(404, 'Short URL not found');
        }

        return response()->json([
            'success' => true,
            'data' => [
                'title' => $link->title,
                'description' => $link->description,
                'original_url' => $link->url,
                'short_url' => $link->short_url,
                'click_count' => $link->click_count,
                'created_at' => $link->created_at,
                'domain' => $link->domain,
                'favicon_url' => $link->favicon_url,
            ],
        ]);
    }

    /**
     * Log access for analytics
     */
    private function logAccess(Request $request, Link $link): void
    {
        // You can implement detailed analytics logging here
        // For now, we just use the built-in click tracking
        
        // Optional: Log additional data like:
        // - User agent
        // - IP address (anonymized)
        // - Referrer
        // - Geographic location
        // - Device type
        
        logger()->info('Short URL accessed', [
            'short_url' => $link->short_url,
            'original_url' => $link->url,
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip(),
            'referrer' => $request->header('referer'),
        ]);
    }
}
