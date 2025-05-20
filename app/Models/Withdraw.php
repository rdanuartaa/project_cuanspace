<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $fillable = ['seller_id', 'amount', 'status'];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
