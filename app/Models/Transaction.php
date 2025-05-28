<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'transaction_code',
        'amount',
        'status',
        'snap_token',
        'download_count',
    ];

    // Relasi ke user (pembeli)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

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
