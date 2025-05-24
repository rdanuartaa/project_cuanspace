<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'admin_id',
        'judul',
        'pesan',
        'penerima',
        'khusus_type',
        'user_id',            // foreign key ke users
        'seller_brand_name',  // foreign key ke sellers (berdasarkan brand_name)
    ];

    protected $casts = [
        'jadwal_kirim' => 'datetime',
    ];

    // Relasi ke user (optional, nullable)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke seller (berdasarkan brand_name)
    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_brand_name', 'brand_name');
    }
}
