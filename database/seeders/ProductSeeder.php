<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'seller_id' => 1, // Ganti dengan ID seller yang valid
                'kategori_id' => 1, // Desain Grafis
                'name' => 'Template Logo',
                'description' => 'Desain logo profesional untuk bisnis Anda',
                'price' => 50000.00,
                'thumbnail' => 'thumbnails/logo-template.jpg',
                'digital_file' => 'files/logo-template.zip',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'kategori_id' => 2, // Pengembangan Web
                'name' => 'Jasa Website',
                'description' => 'Buat website responsif untuk bisnis Anda',
                'price' => 1500000.00,
                'thumbnail' => 'thumbnails/website-service.jpg',
                'digital_file' => 'files/website-service-guide.pdf',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'kategori_id' => 3, // Konten Kreatif
                'name' => 'Video Animasi',
                'description' => 'Buat video animasi untuk promosi',
                'price' => 750000.00,
                'thumbnail' => 'thumbnails/video-animasi.jpg',
                'digital_file' => 'files/video-animasi.mp4',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'kategori_id' => 4, // Produktivitas Bisnis
                'name' => 'Konsultasi Bisnis',
                'description' => 'Konsultasi untuk meningkatkan produktivitas',
                'price' => 1000000.00,
                'thumbnail' => 'thumbnails/konsultasi-bisnis.jpg',
                'digital_file' => 'files/konsultasi-bisnis-guide.pdf',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'kategori_id' => 5, // Edukasi Digital
                'name' => 'Kursus Online',
                'description' => 'Belajar digital marketing dari nol',
                'price' => 300000.00,
                'thumbnail' => 'thumbnails/kursus-online.jpg',
                'digital_file' => 'files/kursus-online.zip',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
