<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    public function definition(): array
    {
        // Ambil produk secara acak
        $product = Product::inRandomOrder()->first();

        // Pastikan user_id tidak sama dengan seller_id dari produk
        $user = User::where('id', '!=', $product->seller_id)
                    ->inRandomOrder()
                    ->first();

        // Pilih salah satu dari 'paid' atau 'cancelled'
        $statusOptions = ['pending', 'paid', 'cancelled'];
        $status = $this->faker->randomElement($statusOptions);

        return [
            'transaction_code' => 'TRX-' . strtoupper(Str::random(8)),
            'user_id' => $user->id,
            'product_id' => $product->id,
            'status' => $status,
            'amount' => $product->price,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
