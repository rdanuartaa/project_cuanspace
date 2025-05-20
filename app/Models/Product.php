<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'kategori_id',
        'name',
        'description',
        'price',
        'thumbnail',
        'digital_file',
        'status'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }



    /**
     * Get the product reviews.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the product transactions.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the average rating.
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    /**
     * Get the total sales count.
     */
    public function getSalesAttribute()
    {
        return $this->transactions()->where('status', 'paid')->count();
    }

    /**
     * Get the total revenue.
     */
    public function getRevenueAttribute()
    {
        return $this->transactions()->where('status', 'paid')->sum('amount');
    }
}
