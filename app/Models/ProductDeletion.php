<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDeletion extends Model
{
    protected $fillable = [
        'product_id', 
        'seller_id', 
        'deletion_reason', 
        'deleted_by'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}