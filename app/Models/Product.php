<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'seller_id',
        'kategori_id',
        'name',
        'description',
        'price',
        'thumbnail',
        'digital_file',
        'status',
    ];

    // Relasi dengan kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Relasi dengan seller
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    // Relasi dengan penghapusan produk
    public function deletion()
    {
        return $this->hasOne(ProductDeletion::class, 'product_id', 'id');
    }

    // Accessor untuk URL thumbnail dengan fallback ke placeholder
    public function getThumbnailUrlAttribute()
    {
        // Cek apakah thumbnail ada dan file exists
        if ($this->thumbnail && Storage::disk('public')->exists('thumbnails/' . $this->thumbnail)) {
            return asset('storage/thumbnails/' . $this->thumbnail);
        }

        // Fallback ke placeholder online
        return "https://via.placeholder.com/300x200/e9ecef/6c757d?text=" . urlencode('Product Image');
    }

    // Accessor untuk URL digital file
    public function getDigitalFileUrlAttribute()
    {
        // Pastikan file ada di storage
        if ($this->digital_file && Storage::disk('public')->exists('digital_files/' . $this->digital_file)) {
            return asset('storage/digital_files/' . $this->digital_file);
        }

        return null;
    }

    // Scope untuk produk yang published
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Scope untuk produk dengan seller aktif
    public function scopeWithActiveSeller($query)
    {
        return $query->whereHas('seller', function($q) {
            $q->where('status', 'active');
        });
    }

    // Accessor untuk format harga
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    // Accessor untuk excerpt description
    public function getExcerptAttribute()
    {
        return \Illuminate\Support\Str::limit($this->description, 100);
    }
}