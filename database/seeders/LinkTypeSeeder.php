<?php

namespace Database\Seeders;

use App\Models\LinkType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LinkTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $linkTypes = [
            [
                'name' => 'Website',
                'description' => 'General websites and web pages',
                'icon' => 'globe-alt',
                'sort_order' => 1,
            ],
            [
                'name' => 'Article',
                'description' => 'Blog posts, news articles, and written content',
                'icon' => 'document-text',
                'sort_order' => 2,
            ],
            [
                'name' => 'Video',
                'description' => 'YouTube, Vimeo, and other video content',
                'icon' => 'video-camera',
                'sort_order' => 3,
            ],
            [
                'name' => 'Repository',
                'description' => 'GitHub, GitLab, and other code repositories',
                'icon' => 'code-bracket',
                'sort_order' => 4,
            ],
            [
                'name' => 'Documentation',
                'description' => 'API docs, technical guides, and manuals',
                'icon' => 'book-open',
                'sort_order' => 5,
            ],
            [
                'name' => 'Tool/Service',
                'description' => 'Online tools, SaaS applications, and services',
                'icon' => 'wrench-screwdriver',
                'sort_order' => 6,
            ],
            [
                'name' => 'Social Profile',
                'description' => 'Social media profiles and accounts',
                'icon' => 'user-circle',
                'sort_order' => 7,
            ],
            [
                'name' => 'Portfolio',
                'description' => 'Personal and company portfolios',
                'icon' => 'briefcase',
                'sort_order' => 8,
            ],
            [
                'name' => 'Reference',
                'description' => 'Reference materials, cheat sheets, and quick guides',
                'icon' => 'academic-cap',
                'sort_order' => 9,
            ],
            [
                'name' => 'Shopping',
                'description' => 'E-commerce sites, product pages, and online stores',
                'icon' => 'shopping-bag',
                'sort_order' => 10,
            ],
        ];

        foreach ($linkTypes as $linkType) {
            LinkType::firstOrCreate(
                ['name' => $linkType['name']],
                $linkType
            );
        }
    }
}
