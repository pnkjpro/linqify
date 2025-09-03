<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Category;
use App\Models\LinkType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LinksController extends Controller
{
    /**
     * Display the links index page.
     */
    public function index(Request $request): Response
    {
        $user = auth()->user();
        
        // Get user's categories and link types for filters
        $categories = Category::forUser($user->id)->ordered()->get();
        $linkTypes = LinkType::active()->ordered()->get();

        // Get query parameters for initial filtering
        $filters = [
            'category_id' => $request->get('category_id'),
            'link_type_id' => $request->get('link_type_id'),
            'is_favorite' => $request->boolean('is_favorite'),
            'search' => $request->get('search'),
        ];

        return Inertia::render('Links/Index', [
            'categories' => $categories,
            'link_types' => $linkTypes,
            'filters' => $filters,
        ]);
    }

    /**
     * Show the create link form.
     */
    public function create(): Response
    {
        $user = auth()->user();
        
        $categories = Category::forUser($user->id)->ordered()->get();
        $linkTypes = LinkType::active()->ordered()->get();

        return Inertia::render('Links/Create', [
            'categories' => $categories,
            'link_types' => $linkTypes,
        ]);
    }

    /**
     * Show the edit link form.
     */
    public function edit(Link $link): Response
    {
        // Ensure user owns this link
        if ($link->user_id !== auth()->id()) {
            abort(404);
        }

        $user = auth()->user();
        $link->load(['category', 'linkType']);
        
        $categories = Category::forUser($user->id)->ordered()->get();
        $linkTypes = LinkType::active()->ordered()->get();

        return Inertia::render('Links/Edit', [
            'link' => $link,
            'categories' => $categories,
            'link_types' => $linkTypes,
        ]);
    }

    /**
     * Show the categories management page.
     */
    public function categories(): Response
    {
        $user = auth()->user();
        
        $categories = Category::forUser($user->id)
            ->withCount('links')
            ->ordered()
            ->get();

        return Inertia::render('Categories/Index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the settings page.
     */
    public function settings(): Response
    {
        $user = auth()->user();
        $settings = $user->settings;
        
        if (!$settings) {
            $settings = \App\Models\UserSettings::createDefaultForUser($user);
        }

        return Inertia::render('Settings', [
            'settings' => $settings,
            'user' => $user,
        ]);
    }
}
