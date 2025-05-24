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
        'read',
        'chat_id',
        'seller_id',
    ];

    protected $casts = [
        'read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_brand_name', 'brand_name');

    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }
}
