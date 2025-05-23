<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Seller;
use App\Models\Kategori;
use App\Models\Product;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat User dummy
        $users = [];
        for ($i = 1; $i <= 5; $i++) {
            $users[] = User::create([
                'name' => "Seller User $i",
                'email' => "seller$i@example.com",
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }

        // 2. Buat kategori jika belum ada
        $kategoris = [
            ['nama_kategori' => 'Desain Grafis', 'slug' => 'desain-grafis'],
            ['nama_kategori' => 'Template UI/UX', 'slug' => 'template-ui-ux'],
            ['nama_kategori' => 'Audio & Musik', 'slug' => 'audio-musik'],
            ['nama_kategori' => 'Animasi', 'slug' => 'animasi'],
            ['nama_kategori' => 'Developer Assets', 'slug' => 'developer-assets'],
            ['nama_kategori' => 'Fotografi', 'slug' => 'fotografi'],
            ['nama_kategori' => 'E-Book', 'slug' => 'e-book'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::firstOrCreate(['nama_kategori' => $kategori['nama_kategori']], $kategori);
        }

        // 3. Buat Seller dari User
        foreach ($users as $index => $user) {
            Seller::create([
                'user_id' => $user->id,
                'brand_name' => "Creative Studio " . ($index + 1),
                'description' => "We are a creative studio specializing in digital products and design services.",
                'contact_email' => $user->email,
                'contact_whatsapp' => '+628123456789' . $index,
                'profile_image' => 'seller-profile-' . ($index + 1) . '.jpg',
                'banner_image' => 'seller-banner-' . ($index + 1) . '.jpg',
                'status' => 'active',
                'balance' => rand(100000, 1000000),
            ]);
        }

        // 4. Buat Produk dummy
        $produkData = [
            [
                'name' => 'Logo Design Template Pack',
                'description' => 'Complete pack of modern logo templates for businesses. Includes 50+ unique designs in various styles.',
                'price' => 150000,
                'kategori' => 'Desain Grafis',
            ],
            [
                'name' => 'Mobile App UI Kit',
                'description' => 'Professional mobile app UI kit with 100+ screens. Perfect for iOS and Android development.',
                'price' => 250000,
                'kategori' => 'Template UI/UX',
            ],
            [
                'name' => 'Royalty Free Music Pack',
                'description' => 'Collection of 20 royalty-free background music tracks. Perfect for videos and presentations.',
                'price' => 300000,
                'kategori' => 'Audio & Musik',
            ],
            [
                'name' => 'Motion Graphics Templates',
                'description' => 'After Effects templates for creating stunning motion graphics and animations.',
                'price' => 400000,
                'kategori' => 'Animasi',
            ],
            [
                'name' => 'React Component Library',
                'description' => 'Reusable React components for faster web development. Includes buttons, forms, and layouts.',
                'price' => 350000,
                'kategori' => 'Developer Assets',
            ],
            [
                'name' => 'Stock Photo Bundle',
                'description' => 'High-quality stock photos for commercial use. 100+ images in various categories.',
                'price' => 200000,
                'kategori' => 'Fotografi',
            ],
            [
                'name' => 'Digital Marketing Guide',
                'description' => 'Complete guide to digital marketing strategies and tactics for modern businesses.',
                'price' => 100000,
                'kategori' => 'E-Book',
            ],
            [
                'name' => 'Brand Identity Package',
                'description' => 'Complete brand identity package including logo, business cards, and letterhead designs.',
                'price' => 500000,
                'kategori' => 'Desain Grafis',
            ],
            [
                'name' => 'Dashboard UI Templates',
                'description' => 'Modern dashboard templates for admin panels and analytics dashboards.',
                'price' => 275000,
                'kategori' => 'Template UI/UX',
            ],
            [
                'name' => 'Podcast Intro Music',
                'description' => 'Professional podcast intro and outro music tracks. Multiple styles available.',
                'price' => 150000,
                'kategori' => 'Audio & Musik',
            ],
            [
                'name' => '2D Character Animation Kit',
                'description' => 'Character animation kit with rigged characters and animation presets.',
                'price' => 450000,
                'kategori' => 'Animasi',
            ],
            [
                'name' => 'CSS Animation Library',
                'description' => 'Collection of CSS animations and transitions for web developers.',
                'price' => 180000,
                'kategori' => 'Developer Assets',
            ],
            [
                'name' => 'Wedding Photography Presets',
                'description' => 'Lightroom presets specifically designed for wedding photography.',
                'price' => 120000,
                'kategori' => 'Fotografi',
            ],
            [
                'name' => 'Startup Business Plan Template',
                'description' => 'Professional business plan template for startups and entrepreneurs.',
                'price' => 80000,
                'kategori' => 'E-Book',
            ],
            [
                'name' => 'Social Media Template Pack',
                'description' => 'Instagram and Facebook post templates for social media marketing.',
                'price' => 95000,
                'kategori' => 'Desain Grafis',
            ],
            [
                'name' => 'E-commerce Website Template',
                'description' => 'Complete e-commerce website template with shopping cart functionality.',
                'price' => 400000,
                'kategori' => 'Template UI/UX',
            ],
            [
                'name' => 'Game Sound Effects Pack',
                'description' => 'Collection of sound effects for game development and interactive media.',
                'price' => 220000,
                'kategori' => 'Audio & Musik',
            ],
            [
                'name' => 'Explainer Video Templates',
                'description' => 'Animated templates for creating explainer and promotional videos.',
                'price' => 380000,
                'kategori' => 'Animasi',
            ],
            [
                'name' => 'Vue.js Component Pack',
                'description' => 'Ready-to-use Vue.js components for rapid application development.',
                'price' => 320000,
                'kategori' => 'Developer Assets',
            ],
            [
                'name' => 'Portrait Photography Bundle',
                'description' => 'Professional portrait photography collection with various poses and lighting.',
                'price' => 250000,
                'kategori' => 'Fotografi',
            ],
        ];

        $sellers = Seller::all();
        $kategoris = Kategori::all();

        foreach ($produkData as $index => $produk) {
            $kategori = $kategoris->firstWhere('nama_kategori', $produk['kategori']);
            $seller = $sellers->get($index % $sellers->count());

            if ($kategori && $seller) {
                Product::create([
                    'seller_id' => $seller->id,
                    'kategori_id' => $kategori->id,
                    'name' => $produk['name'],
                    'description' => $produk['description'],
                    'price' => $produk['price'],
                    'thumbnail' => 'product-thumbnail-' . ($index + 1) . '.jpg',
                    'digital_file' => 'product-file-' . ($index + 1) . '.zip',
                    'status' => 'published',
                ]);
            }
        }

        $this->command->info('Dummy data created successfully!');
        $this->command->info('Created:');
        $this->command->info('- ' . count($users) . ' Users');
        $this->command->info('- ' . count($users) . ' Sellers');
        $this->command->info('- ' . count($kategoris) . ' Categories');
        $this->command->info('- ' . count($produkData) . ' Products');
    }
}