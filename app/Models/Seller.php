<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'brand_name',
        'balance',
        'description',
        'contact_email',
        'contact_whatsapp',
        'profile_image',
        'banner_image',
        'status',
        'bank_name',
        'bank_account',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, Product::class, 'seller_id', 'product_id', 'id', 'id') 
            ->whereIn('transactions.status', ['disetujui', 'paid']);
    }

    public function withdraws()
    {
        return $this->hasMany(Withdraw::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'seller_id', 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'seller_id', 'user_id');
    }

    public function updateBalance()
    {
        $income = $this->transactions()
            ->sum('amount');

        $withdrawn = $this->withdraws()
            ->where('status', 'disetujui')
            ->sum('amount');

        $this->update([
            'balance' => max(0, $income - $withdrawn)
        ]);
    }
}
