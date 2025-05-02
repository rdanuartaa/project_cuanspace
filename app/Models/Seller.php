<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    // Menentukan tabel yang digunakan oleh model ini
    protected $table = 'sellers';

    // Menentukan kolom yang dapat diisi massal
    protected $fillable = [
        'user_id', 'brand_name', 'description', 'contact_email', 'contact_whatsapp', 'profile_image', 'banner_image', 'status',
    ];

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class);  // Setiap Seller berhubungan dengan satu User
    }
}
