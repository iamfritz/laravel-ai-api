<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(PageSeeder::class);

        $admin = \App\Models\User::where('role', 'admin')->first();
        $editor = \App\Models\User::where('role', 'editor')->first();

        if (! $admin || ! $editor) {
            return;
        }

        $categories = [
            'Laravel',
            'Next.js',
            'SEO',
            'Product',
            'Development',
        ];

        foreach ($categories as $categoryName) {
            \App\Models\Category::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($categoryName)],
                ['name' => $categoryName]
            );
        }

        $categoryIds = \App\Models\Category::pluck('id', 'slug')->all();

        $posts = [
            [
                'slug' => 'why-laravel-is-great-for-apis',
                'title' => 'Why Laravel is great for APIs',
                'excerpt' => 'Laravel gives you a clean foundation for secure, readable, and maintainable backend services.',
                'content' => 'Laravel helps teams build reliable APIs with expressive routing, validation, and a developer-friendly ecosystem.',
                'photo' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=1200&q=80',
                'user_id' => $admin->id,
                'category_slug' => 'laravel',
            ],
            [
                'slug' => 'nextjs-for-fast-frontend-dev',
                'title' => 'Next.js for fast frontend development',
                'excerpt' => 'Use a modern React framework to serve content quickly while keeping the user experience polished.',
                'content' => 'Next.js gives you a structured front-end with strong performance, routing, and a great developer experience.',
                'photo' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1200&q=80',
                'user_id' => $editor->id,
                'category_slug' => 'next-js',
            ],
            [
                'slug' => 'building-content-rich-websites',
                'title' => 'Building content-rich websites with a headless CMS',
                'excerpt' => 'Headless content systems make it easier to manage rich pages, metadata, and structured publishing workflows.',
                'content' => 'A CMS architecture separates content authoring from the final presentation layer, helping teams iterate faster across channels.',
                'photo' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1200&q=80',
                'user_id' => $admin->id,
                'category_slug' => 'development',
            ],
            [
                'slug' => 'designing-clear-api-contracts',
                'title' => 'Designing clear API contracts for modern products',
                'excerpt' => 'Consistent payloads and predictable route patterns keep teams aligned as products scale.',
                'content' => 'A disciplined API contract makes client integrations easier, improves testing, and reduces surprise changes during releases.',
                'photo' => 'https://images.unsplash.com/photo-1516321165247-4aa89a48be28?auto=format&fit=crop&w=1200&q=80',
                'user_id' => $editor->id,
                'category_slug' => 'product',
            ],
            [
                'slug' => 'performance-first-frontend-tips',
                'title' => 'Performance-first frontend tips that actually matter',
                'excerpt' => 'Small technical choices around image delivery, rendering, and caching often have the biggest impact.',
                'content' => 'Fast frontends are usually the result of practical engineering decisions: image sizing, lazy loading, and limiting unnecessary re-renders.',
                'photo' => 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?auto=format&fit=crop&w=1200&q=80',
                'user_id' => $admin->id,
                'category_slug' => 'next-js',
            ],
            [
                'slug' => 'securing-authentication-in-apps',
                'title' => 'Securing authentication in modern apps',
                'excerpt' => 'Clear authorization patterns and smart token handling protect both users and business workflows.',
                'content' => 'Authentication should be built with simple guard rules, token rotation policies, and carefully scoped permissions.',
                'photo' => 'https://images.unsplash.com/photo-1558494949cc5c7b1c8ef9e2d6d0a3c6d?auto=format&fit=crop&w=1200&q=80',
                'user_id' => $editor->id,
                'category_slug' => 'development',
            ],
            [
                'slug' => 'working-with-content-metadata',
                'title' => 'Working with content metadata for SEO and sharing',
                'excerpt' => 'Well-structured metadata helps search engines and social platforms understand the page context.',
                'content' => 'Metadata is not just for SEO. It also improves previews, browser behavior, and how content appears when shared across platforms.',
                'photo' => 'https://images.unsplash.com/photo-1432888498266-38ffec3eaf0a?auto=format&fit=crop&w=1200&q=80',
                'user_id' => $admin->id,
                'category_slug' => 'seo',
            ],
            [
                'slug' => 'from-idea-to-production',
                'title' => 'From idea to production: shipping iterative product improvements',
                'excerpt' => 'Small staged releases are often the fastest route to a healthy product lifecycle.',
                'content' => 'By releasing in small, measurable increments, teams learn early, validate assumptions, and reduce the risk of big-bang changes.',
                'photo' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1200&q=80',
                'user_id' => $editor->id,
                'category_slug' => 'product',
            ],
            [
                'slug' => 'migrating-data-with-confidence',
                'title' => 'Migrating data with confidence',
                'excerpt' => 'Smart validation and backups make data changes easier to trust as products evolve.',
                'content' => 'Database migration work is safer when paired with rehearsals, rollback plans, and explicit checks on production assumptions.',
                'photo' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=1200&q=80',
                'user_id' => $admin->id,
                'category_slug' => 'development',
            ],
            [
                'slug' => 'why-component-systems-scale',
                'title' => 'Why component systems scale across larger product teams',
                'excerpt' => 'Reusable UI building blocks reduce churn and accelerate team-wide consistency.',
                'content' => 'When design and engineering share a common component vocabulary, product teams can ship more confidently without reinventing the same patterns.',
                'photo' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1200&q=80',
                'user_id' => $editor->id,
                'category_slug' => 'product',
            ],
            [
                'slug' => 'planning-a-clean-content-workflow',
                'title' => 'Planning a clean content workflow',
                'excerpt' => 'Strong editorial workflows reduce friction between marketing, product, and engineering teams.',
                'content' => 'Content operations work best when publishing stages, review responsibilities, and metadata requirements are clearly defined.',
                'photo' => 'https://images.unsplash.com/photo-1497366412874-3415097a27e7?auto=format&fit=crop&w=1200&q=80',
                'user_id' => $admin->id,
                'category_slug' => 'product',
            ],
            [
                'slug' => 'backend-tools-for-smarter-teams',
                'title' => 'Backend tools that help small teams move faster',
                'excerpt' => 'The right tooling stack gives small teams leverage without introducing governance headaches.',
                'content' => 'Frameworks, command-line tools, and a clean project structure can turn a lean team into a capable product engine.',
                'photo' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1200&q=80',
                'user_id' => $editor->id,
                'category_slug' => 'development',
            ],
        ];

        foreach ($posts as $post) {
            \App\Models\BlogPost::updateOrCreate(
                ['slug' => $post['slug']],
                [
                    'user_id' => $post['user_id'],
                    'category_id' => $categoryIds[$post['category_slug']] ?? null,
                    'title' => $post['title'],
                    'excerpt' => $post['excerpt'],
                    'content' => $post['content'],
                    'photo' => $post['photo'],
                    'status' => 'published',
                ]
            );
        }
    }
}
