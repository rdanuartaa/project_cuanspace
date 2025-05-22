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

    // Accessor untuk URL thumbnail
    public function getThumbnailUrlAttribute()
    {
        // Pastikan file ada di storage
        if ($this->thumbnail && Storage::disk('public')->exists('thumbnails/' . $this->thumbnail)) {
            return asset('storage/thumbnails/' . $this->thumbnail);
        }

        // Fallback ke placeholder jika tidak ada
        return asset('images/placeholder.png');
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
}
