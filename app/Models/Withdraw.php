<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $fillable = [
        'seller_id', 'amount', 'status', 'flip_ref', 'bank_account', 'bank_name'
    ];

    public function seller()
    {
        return $this->belongsTo(seller::class, 'seller_id');
    }
}

