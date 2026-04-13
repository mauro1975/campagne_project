<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Collars',
                'slug'        => 'collars',
                'description' => 'Premium dog collars in various styles, materials and colors.',
                'sort_order'  => 1,
                'is_active'   => true,
            ],
            [
                'name'        => 'Leashes',
                'slug'        => 'leashes',
                'description' => 'Durable and stylish leashes for every walk.',
                'sort_order'  => 2,
                'is_active'   => true,
            ],
            [
                'name'        => 'Coats',
                'slug'        => 'coats',
                'description' => 'Warm and fashionable coats to keep your dog comfortable.',
                'sort_order'  => 3,
                'is_active'   => true,
            ],
            [
                'name'        => 'Harnesses',
                'slug'        => 'harnesses',
                'description' => 'Comfortable harnesses for better control and safety.',
                'sort_order'  => 4,
                'is_active'   => true,
            ],
            [
                'name'        => 'Bag Holders',
                'slug'        => 'bag-holders',
                'description' => 'Convenient poop bag holders for responsible dog owners.',
                'sort_order'  => 5,
                'is_active'   => true,
            ],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
