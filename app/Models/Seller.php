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
         return Transaction::whereHas('product', function ($query) {
        $query->where('seller_id', $this->id);
    });
    }

    public function withdraws()
    {
        return $this->hasMany(Withdraw::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Method untuk update balance
    public function updateBalance()
    {
        $income = $this->transactions()
            ->whereIn('status', ['disetujui', 'paid'])
            ->sum('amount');

        $withdrawn = $this->withdraws()
            ->where('status', 'disetujui')
            ->sum('amount');

        $this->update([
            'balance' => max(0, $income - $withdrawn)
        ]);
    }
}
