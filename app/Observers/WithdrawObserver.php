<?php

namespace App\Observers;

use App\Models\Withdraw;

class WithdrawObserver
{
    /**
     * Handle the Withdraw "created" event.
     */
    public function created(Withdraw $withdraw): void
    {
        if ($withdraw->seller) {
            $withdraw->seller->updateBalance();
        }
    }

    /**
     * Handle the Withdraw "updated" event.
     */
    public function updated(Withdraw $withdraw): void
    {
        // Jika penarikan disetujui
        if ($withdraw->isDirty('status') && $withdraw->status === 'disetujui') {
            if ($withdraw->seller) {
                $withdraw->seller->updateBalance();
            }
        }

        // Jika status lama adalah 'disetujui' tapi sekarang berubah
        $originalStatus = $withdraw->getOriginal('status');
        if ($originalStatus === 'disetujui' && $withdraw->status !== $originalStatus) {
            if ($withdraw->seller) {
                $withdraw->seller->updateBalance();
            }
        }
    }

    /**
     * Handle the Withdraw "deleted" event.
     */
    public function deleted(Withdraw $withdraw): void
    {
        if ($withdraw->seller) {
            $withdraw->seller->updateBalance();
        }
    }
}
