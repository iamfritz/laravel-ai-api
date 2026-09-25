<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home',
                'content' => '<h2>Welcome to Laravel AI</h2><p>We build fast, modern digital products with Laravel APIs and polished Next.js interfaces.</p><p>Our platform is designed for content sites, SaaS products, and business tools that need a reliable foundation.</p>',
                'photo' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'meta_title' => 'Laravel AI | Home',
                'meta_description' => 'Welcome to the Laravel AI home page for modern product development.',
                'meta_keywords' => 'laravel, nextjs, ai, app, homepage',
                'meta_fields' => [
                    'template' => 'home',
                    'featured_image' => '/images/home.jpg',
                    'hero_title' => 'Launch smarter digital experiences.',
                ],
            ]
        );

        Page::updateOrCreate(
            ['slug' => 'about'],
            [
                'title' => 'About',
                'content' => '<p>Laravel AI is a starter architecture for teams that want a clean split between a dependable backend and a polished frontend.</p><p>The Laravel API handles data, validation, and service logic, while Next.js brings the interface to life for end users.</p><p>This structure is ideal for content sites, SaaS products, and internal tools where speed, clarity, and maintainability matter.</p>',
                'photo' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'meta_title' => 'About Laravel AI',
                'meta_description' => 'Learn more about Laravel AI and the technology behind the product.',
                'meta_keywords' => 'about, laravel, nextjs, product architecture',
                'meta_fields' => [
                    'template' => 'about',
                    'team_size' => 12,
                    'focus' => 'Laravel + Next.js',
                ],
            ]
        );
    }
}
