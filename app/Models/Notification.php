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

        'pelaku',
        'status',
        'jadwal_kirim',
        'read',
        'chat_id',
        'seller_id',

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

        'read' => 'boolean',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }

}
