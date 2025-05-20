<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        $kategoris = [
            ['nama_kategori' => 'Desain Grafis', 'slug' => Str::slug('Desain Grafis')],
            ['nama_kategori' => 'Pengembangan Web', 'slug' => Str::slug('Pengembangan Web')],
            ['nama_kategori' => 'Konten Kreatif', 'slug' => Str::slug('Konten Kreatif')],
            ['nama_kategori' => 'Produktivitas Bisnis', 'slug' => Str::slug('Produktivitas Bisnis')],
            ['nama_kategori' => 'Edukasi Digital', 'slug' => Str::slug('Edukasi Digital')],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}
