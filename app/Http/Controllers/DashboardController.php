<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Category;
use App\Models\LinkType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): Response
    {
        $user = auth()->user();
        
        // Get statistics
        $totalLinks = Link::forUser($user->id)->count();
        $totalCategories = Category::forUser($user->id)->count();
        $favoriteLinks = Link::forUser($user->id)->favorites()->count();
        $publicLinks = Link::forUser($user->id)->public()->count();

        // Get recent links (last 7 days)
        $recentLinks = Link::forUser($user->id)
            ->with(['category', 'linkType'])
            ->recent()
            ->limit(10)
            ->get();

        // Get favorite links
        $topFavorites = Link::forUser($user->id)
            ->with(['category', 'linkType'])
            ->favorites()
            ->popular()
            ->limit(8)
            ->get();

        // Get links by category for chart
        $linksByCategory = Category::forUser($user->id)
            ->withCount('links')
            ->having('links_count', '>', 0)
            ->orderBy('links_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'count' => $category->links_count,
                    'color' => $category->color,
                ];
            });

        // Get popular links (most clicked)
        $popularLinks = Link::forUser($user->id)
            ->with(['category', 'linkType'])
            ->where('click_count', '>', 0)
            ->popular()
            ->limit(5)
            ->get();

        // Get recent activity (links added in the last 30 days grouped by date)
        $recentActivity = Link::forUser($user->id)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'count' => $item->count,
                ];
            });

        // Get link types distribution
        $linkTypeDistribution = Link::forUser($user->id)
            ->join('link_types', 'links.link_type_id', '=', 'link_types.id')
            ->selectRaw('link_types.name, link_types.icon, COUNT(*) as count')
            ->groupBy('link_types.id', 'link_types.name', 'link_types.icon')
            ->orderBy('count', 'desc')
            ->limit(8)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'icon' => $item->icon,
                    'count' => $item->count,
                ];
            });

        return Inertia::render('Dashboard', [
            'statistics' => [
                'total_links' => $totalLinks,
                'total_categories' => $totalCategories,
                'favorite_links' => $favoriteLinks,
                'public_links' => $publicLinks,
            ],
            'recent_links' => $recentLinks,
            'favorite_links' => $topFavorites,
            'popular_links' => $popularLinks,
            'links_by_category' => $linksByCategory,
            'recent_activity' => $recentActivity,
            'link_type_distribution' => $linkTypeDistribution,
        ]);
    }
}
