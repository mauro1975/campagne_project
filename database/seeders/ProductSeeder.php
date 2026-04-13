<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $collars    = Category::where('slug', 'collars')->first();
        $leashes    = Category::where('slug', 'leashes')->first();
        $coats      = Category::where('slug', 'coats')->first();
        $harnesses  = Category::where('slug', 'harnesses')->first();
        $bagHolders = Category::where('slug', 'bag-holders')->first();

        $colorSets = [
            'classic' => json_encode([
                ['name' => 'Black',  'hex' => '#1a1a1a'],
                ['name' => 'Brown',  'hex' => '#8B4513'],
                ['name' => 'Red',    'hex' => '#e74c3c'],
            ]),
            'pastels' => json_encode([
                ['name' => 'Mint',   'hex' => '#9ad7a0'],
                ['name' => 'Blush',  'hex' => '#f4a7b9'],
                ['name' => 'Sky',    'hex' => '#87ceeb'],
            ]),
            'neutral' => json_encode([
                ['name' => 'Beige',  'hex' => '#f5f0e8'],
                ['name' => 'Grey',   'hex' => '#999999'],
                ['name' => 'Navy',   'hex' => '#1a237e'],
            ]),
        ];

        $sizes = json_encode(['XS', 'S', 'M', 'L', 'XL']);
        $collarSizes = json_encode(['XS', 'S', 'M', 'L']);

        $products = [
            // Collars
            [
                'category_id'       => $collars?->id,
                'name'              => 'Genuine Leather Collar',
                'slug'              => 'genuine-leather-collar',
                'description'       => 'Handcrafted from premium full-grain leather. Soft on your dog\'s neck, durable for everyday use. Brass buckle and D-ring.',
                'price'             => 29.99,
                'available_colors'  => $colorSets['classic'],
                'available_sizes'   => $collarSizes,
                'is_active'         => true,
                'is_featured'       => true,
                'is_best_seller'    => true,
                'discount_percent'  => 0,
            ],
            [
                'category_id'       => $collars?->id,
                'name'              => 'Pastel Nylon Collar',
                'slug'              => 'pastel-nylon-collar',
                'description'       => 'Lightweight and water-resistant nylon collar in beautiful pastel tones. Perfect for active dogs.',
                'price'             => 14.99,
                'available_colors'  => $colorSets['pastels'],
                'available_sizes'   => $collarSizes,
                'is_active'         => true,
                'is_featured'       => false,
                'is_best_seller'    => false,
                'discount_percent'  => 10,
            ],
            // Leashes
            [
                'category_id'       => $leashes?->id,
                'name'              => 'Braided Leather Leash',
                'slug'              => 'braided-leather-leash',
                'description'       => 'Elegant braided leather leash, 1.5m length. Comfortable padded handle. Available in classic colors.',
                'price'             => 34.99,
                'available_colors'  => $colorSets['classic'],
                'available_sizes'   => null,
                'is_active'         => true,
                'is_featured'       => true,
                'is_best_seller'    => true,
                'discount_percent'  => 0,
            ],
            [
                'category_id'       => $leashes?->id,
                'name'              => 'Retractable Dog Leash',
                'slug'              => 'retractable-dog-leash',
                'description'       => 'Extends up to 5 meters for maximum freedom. One-button brake and lock. Suitable for dogs up to 25kg.',
                'price'             => 22.99,
                'available_colors'  => $colorSets['neutral'],
                'available_sizes'   => null,
                'is_active'         => true,
                'is_featured'       => false,
                'is_best_seller'    => false,
                'discount_percent'  => 0,
            ],
            // Coats
            [
                'category_id'       => $coats?->id,
                'name'              => 'Winter Puffer Coat',
                'slug'              => 'winter-puffer-coat',
                'description'       => 'Keeps your dog warm during cold winter walks. Water-repellent outer shell, cosy inner lining. Full belly coverage.',
                'price'             => 49.99,
                'available_colors'  => $colorSets['neutral'],
                'available_sizes'   => $sizes,
                'is_active'         => true,
                'is_featured'       => true,
                'is_best_seller'    => false,
                'discount_percent'  => 15,
            ],
            [
                'category_id'       => $coats?->id,
                'name'              => 'Raincoat with Hood',
                'slug'              => 'raincoat-with-hood',
                'description'       => 'Fully waterproof with adjustable hood. Velcro belly strap for secure fit. Easy to clean.',
                'price'             => 39.99,
                'available_colors'  => $colorSets['pastels'],
                'available_sizes'   => $sizes,
                'is_active'         => true,
                'is_featured'       => false,
                'is_best_seller'    => true,
                'discount_percent'  => 0,
            ],
            // Harnesses
            [
                'category_id'       => $harnesses?->id,
                'name'              => 'No-Pull Step-In Harness',
                'slug'              => 'no-pull-step-in-harness',
                'description'       => 'Easy step-in design with front and back leash attachments. Padded chest plate for comfort. Reflective strips for night safety.',
                'price'             => 44.99,
                'available_colors'  => $colorSets['classic'],
                'available_sizes'   => $sizes,
                'is_active'         => true,
                'is_featured'       => true,
                'is_best_seller'    => true,
                'discount_percent'  => 0,
            ],
            [
                'category_id'       => $harnesses?->id,
                'name'              => 'Adventure Hiking Harness',
                'slug'              => 'adventure-hiking-harness',
                'description'       => 'Heavy-duty harness for outdoor adventures. Handle on back for assistance on rough terrain. Multiple adjustment points.',
                'price'             => 59.99,
                'available_colors'  => $colorSets['neutral'],
                'available_sizes'   => $sizes,
                'is_active'         => true,
                'is_featured'       => false,
                'is_best_seller'    => false,
                'discount_percent'  => 0,
            ],
            // Bag Holders
            [
                'category_id'       => $bagHolders?->id,
                'name'              => 'Leather Bag Holder Keychain',
                'slug'              => 'leather-bag-holder-keychain',
                'description'       => 'Stylish leather pouch that attaches to any leash or bag. Includes 1 roll of bags. Never forget bags again.',
                'price'             => 12.99,
                'available_colors'  => $colorSets['classic'],
                'available_sizes'   => null,
                'is_active'         => true,
                'is_featured'       => false,
                'is_best_seller'    => false,
                'discount_percent'  => 0,
            ],
            [
                'category_id'       => $bagHolders?->id,
                'name'              => 'Silicone Bag Dispenser',
                'slug'              => 'silicone-bag-dispenser',
                'description'       => 'Bright and easy-to-find silicone dispenser. Holds standard rolls. Comes in fun colors. Includes 2 rolls of eco-friendly bags.',
                'price'             => 9.99,
                'available_colors'  => $colorSets['pastels'],
                'available_sizes'   => null,
                'is_active'         => true,
                'is_featured'       => false,
                'is_best_seller'    => false,
                'discount_percent'  => 20,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['slug' => $product['slug']], $product);
        }
    }
}
