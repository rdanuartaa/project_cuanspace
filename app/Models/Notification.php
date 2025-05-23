<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'admin_id',
        'judul',
        'pesan',
        'pelaku',
        'status',
        'jadwal_kirim',
        'read',
        'chat_id',
        'seller_id',
    ];

    protected $casts = [
        'jadwal_kirim' => 'datetime',
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

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
