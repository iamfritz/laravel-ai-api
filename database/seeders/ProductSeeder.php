<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'electronics' => 'Electronics',
            'home' => 'Home',
            'office' => 'Office',
            'accessories' => 'Accessories',
            'lifestyle' => 'Lifestyle',
        ];

        foreach ($categories as $slug => $name) {
            Category::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name]
            );
        }

        $categoryIds = Category::pluck('id', 'slug')->all();

        $products = [
            [
                'title' => 'Wireless Noise Cancelling Headphones',
                'slug' => 'wireless-noise-cancelling-headphones',
                'category_slug' => 'electronics',
                'description' => 'Premium over-ear headphones engineered for immersive sound, all-day comfort, and low-noise travel.',
                'image' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=1200&q=80',
                'sku' => 'WNC-1001',
                'price' => 249.99,
                'tags' => ['audio', 'wireless', 'travel', 'premium'],
                'custom_fields' => [
                    ['key' => 'Battery Life', 'value' => '30 hours'],
                    ['key' => 'Connectivity', 'value' => 'Bluetooth 5.3'],
                    ['key' => 'Weight', 'value' => '250g'],
                ],
                'variations' => [
                    ['name' => 'Color', 'options' => ['Black', 'Silver']],
                    ['name' => 'Bundle', 'options' => ['Standard', 'Travel Case']],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Smart Home Hub',
                'slug' => 'smart-home-hub',
                'category_slug' => 'home',
                'description' => 'A compact voice-enabled smart hub for lighting, security, and climate automation in one simple setup.',
                'image' => 'https://images.unsplash.com/photo-1558002038-1055907df827?auto=format&fit=crop&w=1200&q=80',
                'sku' => 'SMH-2002',
                'price' => 179.00,
                'tags' => ['smart-home', 'automation', 'security'],
                'custom_fields' => [
                    ['key' => 'Voice Assistant', 'value' => 'Built-in'],
                    ['key' => 'Connectivity', 'value' => 'Wi-Fi / Bluetooth'],
                ],
                'variations' => [
                    ['name' => 'Finish', 'options' => ['White', 'Black']],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Premium Laptop Stand',
                'slug' => 'premium-laptop-stand',
                'category_slug' => 'office',
                'description' => 'Aluminum laptop stand that improves posture, airflow, and comfort during long work sessions.',
                'image' => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1200&q=80',
                'sku' => 'LPS-3003',
                'price' => 118.50,
                'tags' => ['workspace', 'ergonomic', 'office'],
                'custom_fields' => [
                    ['key' => 'Material', 'value' => 'Aluminum'],
                    ['key' => 'Height', 'value' => '14 cm'],
                ],
                'variations' => [
                    ['name' => 'Size', 'options' => ['13"', '14"', '15"']],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Ergonomic Office Chair',
                'slug' => 'ergonomic-office-chair',
                'category_slug' => 'office',
                'description' => 'Supportive seating with lumbar adjustment and breathable mesh for all-day productivity.',
                'image' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
                'sku' => 'EOC-4004',
                'price' => 429.00,
                'tags' => ['office', 'ergonomic', 'furniture'],
                'custom_fields' => [
                    ['key' => 'Material', 'value' => 'Mesh and Nylon'],
                    ['key' => 'Weight Capacity', 'value' => '120kg'],
                ],
                'variations' => [
                    ['name' => 'Color', 'options' => ['Gray', 'Black']],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Leather Travel Bag',
                'slug' => 'leather-travel-bag',
                'category_slug' => 'lifestyle',
                'description' => 'A refined leather carry-all made for daily commuting, weekend escapes, and polished travel.',
                'image' => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?auto=format&fit=crop&w=1200&q=80',
                'sku' => 'LTB-5005',
                'price' => 265.00,
                'tags' => ['travel', 'leather', 'lifestyle'],
                'custom_fields' => [
                    ['key' => 'Capacity', 'value' => '32L'],
                    ['key' => 'Material', 'value' => 'Full-grain leather'],
                ],
                'variations' => [
                    ['name' => 'Color', 'options' => ['Tan', 'Black']],
                ],
                'status' => 'published',
            ],
            [
                'title' => '4K Action Camera',
                'slug' => '4k-action-camera',
                'category_slug' => 'electronics',
                'description' => 'Compact action camera with 4K stabilization, waterproof housing, and sharp motion capture.',
                'image' => 'https://images.unsplash.com/photo-1511497584788-876760111969?auto=format&fit=crop&w=1200&q=80',
                'sku' => 'AAC-6006',
                'price' => 399.99,
                'tags' => ['camera', 'travel', 'action'],
                'custom_fields' => [
                    ['key' => 'Resolution', 'value' => '4K UHD'],
                    ['key' => 'Waterproof', 'value' => '10m'],
                ],
                'variations' => [
                    ['name' => 'Accessory Kit', 'options' => ['Basic', 'Pro Bundle']],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Ceramic Coffee Set',
                'slug' => 'ceramic-coffee-set',
                'category_slug' => 'home',
                'description' => 'Minimal ceramic coffee set for slow mornings, handcrafted detail, and elevated home rituals.',
                'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1200&q=80',
                'sku' => 'CCS-7007',
                'price' => 74.00,
                'tags' => ['home', 'kitchen', 'minimal'],
                'custom_fields' => [
                    ['key' => 'Material', 'value' => 'Stoneware'],
                    ['key' => 'Set Includes', 'value' => '2 mugs + 1 carafe'],
                ],
                'variations' => [
                    ['name' => 'Finish', 'options' => ['Sand', 'Charcoal']],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Solar Powered Backpack',
                'slug' => 'solar-powered-backpack',
                'category_slug' => 'accessories',
                'description' => 'A weather-ready backpack with integrated solar charging for commuting and outdoor adventures.',
                'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=1200&q=80',
                'sku' => 'SPB-8008',
                'price' => 189.00,
                'tags' => ['outdoor', 'solar', 'travel'],
                'custom_fields' => [
                    ['key' => 'Capacity', 'value' => '28L'],
                    ['key' => 'Charge Time', 'value' => '6 hours'],
                ],
                'variations' => [
                    ['name' => 'Color', 'options' => ['Forest', 'Slate']],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Wireless Mechanical Keyboard',
                'slug' => 'wireless-mechanical-keyboard',
                'category_slug' => 'electronics',
                'description' => 'Quiet mechanical keyboard with premium keys, low-latency wireless connectivity, and a compact layout.',
                'image' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=1200&q=80',
                'sku' => 'WMK-9009',
                'price' => 159.99,
                'tags' => ['keyboard', 'workspace', 'wireless'],
                'custom_fields' => [
                    ['key' => 'Switch Type', 'value' => 'Silent Brown'],
                    ['key' => 'Battery', 'value' => 'Up to 30 days'],
                ],
                'variations' => [
                    ['name' => 'Layout', 'options' => ['75%', 'Compact']],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Bamboo Desk Organizer',
                'slug' => 'bamboo-desk-organizer',
                'category_slug' => 'office',
                'description' => 'Natural bamboo organizer that keeps essentials neat while bringing warmth to your desk.',
                'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1200&q=80',
                'sku' => 'BDO-1010',
                'price' => 58.00,
                'tags' => ['desk', 'minimal', 'bamboo'],
                'custom_fields' => [
                    ['key' => 'Material', 'value' => 'Bamboo'],
                    ['key' => 'Slots', 'value' => '6 compartments'],
                ],
                'variations' => [
                    ['name' => 'Size', 'options' => ['Small', 'Large']],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Fitness Smartwatch',
                'slug' => 'fitness-smartwatch',
                'category_slug' => 'lifestyle',
                'description' => 'A sleek fitness tracker with health insights, sleep monitoring, and everyday style.',
                'image' => 'https://images.unsplash.com/photo-1516574187841-cb9cc2ca948b?auto=format&fit=crop&w=1200&q=80',
                'sku' => 'FSW-1111',
                'price' => 219.00,
                'tags' => ['fitness', 'health', 'wearables'],
                'custom_fields' => [
                    ['key' => 'Display', 'value' => 'AMOLED 1.7"'],
                    ['key' => 'Health Tracking', 'value' => 'HR + Sleep'],
                ],
                'variations' => [
                    ['name' => 'Band', 'options' => ['Black', 'Olive', 'Rose Gold']],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Portable Smart Speaker',
                'slug' => 'portable-smart-speaker',
                'category_slug' => 'home',
                'description' => 'Compact speaker with crisp sound, water resistance, and seamless Bluetooth pairing.',
                'image' => 'https://images.unsplash.com/photo-1545454675-3531b543be5d?auto=format&fit=crop&w=1200&q=80',
                'sku' => 'PSS-1212',
                'price' => 129.00,
                'tags' => ['audio', 'home', 'portable'],
                'custom_fields' => [
                    ['key' => 'Battery', 'value' => '18 hours'],
                    ['key' => 'Waterproof', 'value' => 'IPX7'],
                ],
                'variations' => [
                    ['name' => 'Color', 'options' => ['Teal', 'Sand']],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Air Purifier',
                'slug' => 'air-purifier',
                'category_slug' => 'home',
                'description' => 'Quiet air purifier that removes dust and allergens while keeping indoor air fresh and balanced.',
                'image' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=1200&q=80',
                'sku' => 'AIR-1313',
                'price' => 289.00,
                'tags' => ['home', 'wellness', 'clean-air'],
                'custom_fields' => [
                    ['key' => 'Coverage', 'value' => '35m²'],
                    ['key' => 'Filter Type', 'value' => 'HEPA'],
                ],
                'variations' => [
                    ['name' => 'Finish', 'options' => ['White', 'Graphite']],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Minimal Desk Lamp',
                'slug' => 'minimal-desk-lamp',
                'category_slug' => 'office',
                'description' => 'Warm ambient desk lamp with touch controls and a sleek, space-saving silhouette.',
                'image' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
                'sku' => 'MDL-1414',
                'price' => 89.00,
                'tags' => ['lighting', 'office', 'minimal'],
                'custom_fields' => [
                    ['key' => 'Brightness', 'value' => '500 lumens'],
                    ['key' => 'Color Temp', 'value' => '3000K'],
                ],
                'variations' => [
                    ['name' => 'Color', 'options' => ['Warm White', 'Neutral White']],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Bluetooth Earbuds',
                'slug' => 'bluetooth-earbuds',
                'category_slug' => 'electronics',
                'description' => 'Comfortable wireless earbuds with rich sound, fast charging, and reliable daily performance.',
                'image' => 'https://images.unsplash.com/photo-1606220588913-b3aacb4d2f46?auto=format&fit=crop&w=1200&q=80',
                'sku' => 'BTE-1515',
                'price' => 139.99,
                'tags' => ['audio', 'wireless', 'portable'],
                'custom_fields' => [
                    ['key' => 'Battery', 'value' => '24 hours total'],
                    ['key' => 'Water Resistance', 'value' => 'IPX4'],
                ],
                'variations' => [
                    ['name' => 'Case', 'options' => ['Standard', 'Charging Case Pro']],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Mini Projector',
                'slug' => 'mini-projector',
                'category_slug' => 'home',
                'description' => 'Portable projector for movie nights, presentations, and flexible home entertainment setups.',
                'image' => 'https://images.unsplash.com/photo-1516321165247-4aa89a48be28?auto=format&fit=crop&w=1200&q=80',
                'sku' => 'MPR-1616',
                'price' => 349.00,
                'tags' => ['projector', 'home', 'cinema'],
                'custom_fields' => [
                    ['key' => 'Brightness', 'value' => '500 ANSI'],
                    ['key' => 'Throw Ratio', 'value' => '1.2:1'],
                ],
                'variations' => [
                    ['name' => 'Mount', 'options' => ['Table', 'Ceiling']],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Standing Desk Converter',
                'slug' => 'standing-desk-converter',
                'category_slug' => 'office',
                'description' => 'Easy-to-adjust standing desk converter that transforms an existing desk into a healthier workstation.',
                'image' => 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?auto=format&fit=crop&w=1200&q=80',
                'sku' => 'SDC-1717',
                'price' => 269.00,
                'tags' => ['office', 'health', 'workspace'],
                'custom_fields' => [
                    ['key' => 'Lift Range', 'value' => '15-30 cm'],
                    ['key' => 'Load Capacity', 'value' => '30kg'],
                ],
                'variations' => [
                    ['name' => 'Finish', 'options' => ['White', 'Black']],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Insulated Water Bottle',
                'slug' => 'insulated-water-bottle',
                'category_slug' => 'lifestyle',
                'description' => 'Vacuum-insulated bottle designed to keep drinks hot or cold for hours on the go.',
                'image' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?auto=format&fit=crop&w=1200&q=80',
                'sku' => 'IWB-1818',
                'price' => 42.00,
                'tags' => ['hydration', 'travel', 'lifestyle'],
                'custom_fields' => [
                    ['key' => 'Capacity', 'value' => '750ml'],
                    ['key' => 'Temp Hold', 'value' => '24 hours'],
                ],
                'variations' => [
                    ['name' => 'Color', 'options' => ['Blue', 'Green', 'Sand']],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Compact Mirrorless Camera',
                'slug' => 'compact-mirrorless-camera',
                'category_slug' => 'electronics',
                'description' => 'Portable mirrorless camera with crisp image quality and pro-level creative modes.',
                'image' => 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=1200&q=80',
                'sku' => 'CMC-1919',
                'price' => 899.00,
                'tags' => ['camera', 'creative', 'travel'],
                'custom_fields' => [
                    ['key' => 'Sensor', 'value' => '24MP'],
                    ['key' => 'Video', 'value' => '4K 60fps'],
                ],
                'variations' => [
                    ['name' => 'Lens Kit', 'options' => ['18-55mm', '24-70mm']],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Productivity Notebook Set',
                'slug' => 'productivity-notebook-set',
                'category_slug' => 'office',
                'description' => 'Thoughtful notebook set built for planning, journaling, and focused daily work.',
                'image' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=1200&q=80',
                'sku' => 'PNS-2020',
                'price' => 36.00,
                'tags' => ['planning', 'office', 'stationery'],
                'custom_fields' => [
                    ['key' => 'Included', 'value' => '3 notebooks + pen'],
                    ['key' => 'Paper', 'value' => '80 GSM'],
                ],
                'variations' => [
                    ['name' => 'Style', 'options' => ['Classic', 'Bold']],
                ],
                'status' => 'published',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => $product['slug']],
                [
                    'user_id' => null,
                    'category_id' => $categoryIds[$product['category_slug']] ?? null,
                    'title' => $product['title'],
                    'description' => $product['description'],
                    'image' => $product['image'],
                    'sku' => $product['sku'],
                    'price' => $product['price'],
                    'tags' => $product['tags'],
                    'custom_fields' => $product['custom_fields'],
                    'variations' => $product['variations'],
                    'status' => $product['status'],
                ]
            );
        }
    }
}
