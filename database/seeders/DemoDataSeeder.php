<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\LinkType;
use App\Models\Link;
use App\Models\UserSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create demo user
        $demoUser = User::firstOrCreate(
            ['email' => 'demo@linkmanager.test'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Create user settings for demo user
        UserSettings::firstOrCreate(
            ['user_id' => $demoUser->id],
            [
                'theme' => 'light',
                'default_view' => 'grid',
                'links_per_page' => 20,
                'show_descriptions' => true,
                'show_favicons' => true,
                'auto_generate_thumbnails' => true,
                'default_link_privacy' => 'private',
                'dashboard_widgets' => ['recent_links', 'favorite_links', 'link_statistics', 'category_overview'],
                'notification_preferences' => [
                    'email_weekly_summary' => true,
                    'email_link_recommendations' => false,
                    'browser_new_link_notification' => true,
                    'browser_import_complete' => true,
                ],
            ]
        );

        // Create default categories for demo user
        $categories = [
            [
                'name' => 'Work & Productivity',
                'description' => 'Professional tools, project management, and productivity apps',
                'color' => '#3B82F6',
                'icon' => 'briefcase',
                'sort_order' => 1,
            ],
            [
                'name' => 'Learning & Education',
                'description' => 'Courses, tutorials, and educational resources',
                'color' => '#10B981',
                'icon' => 'academic-cap',
                'sort_order' => 2,
            ],
            [
                'name' => 'Social Media',
                'description' => 'Social networks and communication platforms',
                'color' => '#8B5CF6',
                'icon' => 'users',
                'sort_order' => 3,
            ],
            [
                'name' => 'Entertainment',
                'description' => 'Movies, music, games, and leisure content',
                'color' => '#F59E0B',
                'icon' => 'film',
                'sort_order' => 4,
            ],
            [
                'name' => 'Development Tools',
                'description' => 'Code editors, frameworks, and developer resources',
                'color' => '#EF4444',
                'icon' => 'code-bracket',
                'sort_order' => 5,
            ],
            [
                'name' => 'News & Reading',
                'description' => 'News sources, blogs, and reading materials',
                'color' => '#06B6D4',
                'icon' => 'newspaper',
                'sort_order' => 6,
            ],
            [
                'name' => 'Shopping',
                'description' => 'E-commerce sites and online stores',
                'color' => '#EC4899',
                'icon' => 'shopping-bag',
                'sort_order' => 7,
            ],
            [
                'name' => 'Travel',
                'description' => 'Travel resources, booking sites, and guides',
                'color' => '#84CC16',
                'icon' => 'globe-americas',
                'sort_order' => 8,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                [
                    'user_id' => $demoUser->id,
                    'name' => $categoryData['name']
                ],
                array_merge($categoryData, ['user_id' => $demoUser->id])
            );
        }

        // Get created categories and link types
        $userCategories = Category::where('user_id', $demoUser->id)->get()->keyBy('name');
        $linkTypes = LinkType::all()->keyBy('name');

        // Create sample links
        $sampleLinks = [
            [
                'title' => 'GitHub',
                'url' => 'https://github.com',
                'description' => 'The world\'s leading software development platform',
                'category' => 'Development Tools',
                'link_type' => 'Website',
                'tags' => ['development', 'git', 'code'],
                'is_favorite' => true,
            ],
            [
                'title' => 'Laravel Documentation',
                'url' => 'https://laravel.com/docs',
                'description' => 'The official Laravel framework documentation',
                'category' => 'Development Tools',
                'link_type' => 'Documentation',
                'tags' => ['laravel', 'php', 'framework'],
                'is_favorite' => true,
            ],
            [
                'title' => 'Vue.js Guide',
                'url' => 'https://vuejs.org/guide/',
                'description' => 'The progressive JavaScript framework guide',
                'category' => 'Development Tools',
                'link_type' => 'Documentation',
                'tags' => ['vue', 'javascript', 'frontend'],
            ],
            [
                'title' => 'Tailwind CSS',
                'url' => 'https://tailwindcss.com',
                'description' => 'A utility-first CSS framework',
                'category' => 'Development Tools',
                'link_type' => 'Website',
                'tags' => ['css', 'tailwind', 'design'],
                'is_favorite' => true,
            ],
            [
                'title' => 'Coursera',
                'url' => 'https://coursera.org',
                'description' => 'Online courses from top universities',
                'category' => 'Learning & Education',
                'link_type' => 'Website',
                'tags' => ['education', 'courses', 'learning'],
            ],
            [
                'title' => 'freeCodeCamp',
                'url' => 'https://freecodecamp.org',
                'description' => 'Learn to code for free',
                'category' => 'Learning & Education',
                'link_type' => 'Website',
                'tags' => ['coding', 'free', 'tutorial'],
                'is_favorite' => true,
            ],
            [
                'title' => 'LinkedIn',
                'url' => 'https://linkedin.com',
                'description' => 'Professional networking platform',
                'category' => 'Social Media',
                'link_type' => 'Social Profile',
                'tags' => ['professional', 'networking', 'career'],
            ],
            [
                'title' => 'Twitter',
                'url' => 'https://twitter.com',
                'description' => 'Social media platform for news and updates',
                'category' => 'Social Media',
                'link_type' => 'Social Profile',
                'tags' => ['social', 'news', 'updates'],
            ],
            [
                'title' => 'Netflix',
                'url' => 'https://netflix.com',
                'description' => 'Streaming movies and TV shows',
                'category' => 'Entertainment',
                'link_type' => 'Website',
                'tags' => ['streaming', 'movies', 'tv'],
                'is_favorite' => true,
            ],
            [
                'title' => 'Spotify',
                'url' => 'https://spotify.com',
                'description' => 'Music streaming service',
                'category' => 'Entertainment',
                'link_type' => 'Website',
                'tags' => ['music', 'streaming', 'audio'],
            ],
            [
                'title' => 'Trello',
                'url' => 'https://trello.com',
                'description' => 'Project management and collaboration tool',
                'category' => 'Work & Productivity',
                'link_type' => 'Tool/Service',
                'tags' => ['project-management', 'collaboration', 'productivity'],
                'is_favorite' => true,
            ],
            [
                'title' => 'Slack',
                'url' => 'https://slack.com',
                'description' => 'Team communication platform',
                'category' => 'Work & Productivity',
                'link_type' => 'Tool/Service',
                'tags' => ['communication', 'team', 'chat'],
            ],
            [
                'title' => 'Hacker News',
                'url' => 'https://news.ycombinator.com',
                'description' => 'Tech news and discussion',
                'category' => 'News & Reading',
                'link_type' => 'Website',
                'tags' => ['tech', 'news', 'discussion'],
                'is_favorite' => true,
            ],
            [
                'title' => 'TechCrunch',
                'url' => 'https://techcrunch.com',
                'description' => 'Startup and technology news',
                'category' => 'News & Reading',
                'link_type' => 'Website',
                'tags' => ['startup', 'technology', 'business'],
            ],
            [
                'title' => 'Amazon',
                'url' => 'https://amazon.com',
                'description' => 'Online shopping marketplace',
                'category' => 'Shopping',
                'link_type' => 'Website',
                'tags' => ['shopping', 'ecommerce', 'marketplace'],
            ],
            [
                'title' => 'Booking.com',
                'url' => 'https://booking.com',
                'description' => 'Hotel and travel booking',
                'category' => 'Travel',
                'link_type' => 'Website',
                'tags' => ['travel', 'hotels', 'booking'],
            ],
        ];

        foreach ($sampleLinks as $linkData) {
            $category = $userCategories[$linkData['category']] ?? null;
            $linkType = $linkTypes[$linkData['link_type']] ?? null;

            if ($category && $linkType) {
                Link::firstOrCreate(
                    [
                        'user_id' => $demoUser->id,
                        'url' => $linkData['url']
                    ],
                    [
                        'user_id' => $demoUser->id,
                        'category_id' => $category->id,
                        'link_type_id' => $linkType->id,
                        'title' => $linkData['title'],
                        'url' => $linkData['url'],
                        'description' => $linkData['description'],
                        'tags' => $linkData['tags'],
                        'is_favorite' => $linkData['is_favorite'] ?? false,
                        'is_public' => false,
                        'click_count' => rand(0, 50),
                        'last_accessed_at' => rand(0, 1) ? now()->subDays(rand(1, 30)) : null,
                    ]
                );
            }
        }
    }
}
