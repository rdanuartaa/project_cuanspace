<?php

namespace App\Observers;

use App\Models\Transaction;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        if ($transaction->product && $transaction->product->seller) {
            $transaction->product->seller->updateBalance();
        }
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        // Jika status transaksi berubah ke 'berhasil' atau 'paid'
        if ($transaction->isDirty('status') && in_array($transaction->status, ['berhasil', 'paid'])) {
            if ($transaction->product && $transaction->product->seller) {
                $transaction->product->seller->updateBalance();
            }
        }

        // Jika status lama adalah 'berhasil'/'paid' tapi sekarang berubah
        $originalStatus = $transaction->getOriginal('status');
        if (in_array($originalStatus, ['berhasil', 'paid']) && $transaction->status !== $originalStatus) {
            if ($transaction->product && $transaction->product->seller) {
                $transaction->product->seller->updateBalance();
            }
        }
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        if ($transaction->product && $transaction->product->seller) {
            $transaction->product->seller->updateBalance();
        }
    }
}
