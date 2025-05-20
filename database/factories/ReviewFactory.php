<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Review;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        do {
            // Ambil transaksi sukses
            $transaction = Transaction::where('status', 'paid')
                ->inRandomOrder()
                ->first();

            if (!$transaction) {
                $transaction = Transaction::factory()->create(['status' => 'paid']);
            }

            // Cek apakah sudah ada review untuk user & produk ini
            $reviewExists = Review::where('user_id', $transaction->user_id)
                ->where('product_id', $transaction->product_id)
                ->exists();

        } while ($reviewExists); // ulangi sampai ketemu transaksi yang belum direview

        return [
            'user_id' => $transaction->user_id,
            'product_id' => $transaction->product_id,
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->optional()->sentence(10) ?? 'Tidak ada komentar',
        ];
    }
}
