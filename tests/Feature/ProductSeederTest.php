<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_seeder_creates_twenty_products(): void
    {
        $this->seed([
            \Database\Seeders\UserSeeder::class,
            \Database\Seeders\PageSeeder::class,
            \Database\Seeders\ProductSeeder::class,
        ]);

        $this->assertDatabaseCount('products', 20);
        $this->assertDatabaseHas('products', ['status' => 'published']);
    }
}
