<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'brand_name', 'description', 'contact_email', 
        'contact_whatsapp', 'profile_image', 'banner_image', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}