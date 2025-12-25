<?php

namespace App\Observers;

use App\Models\Wallet;

class WalletObserver
{
    /**
     * Handle the Wallet "created" event.
     */
    public function creating(Wallet $wallet): void
    {
        do {
            $wallet_number = 'TR' . implode('', array_map(fn() => rand(0, 9), range(1, rand(14,20))));
        } while (Wallet::where('wallet_number', $wallet_number)->exists());

        $wallet->wallet_number = $wallet_number;
    }


    /**
     * Handle the Wallet "updated" event.
     */
    public function updated(Wallet $wallet): void
    {
        //
    }

    /**
     * Handle the Wallet "deleted" event.
     */
    public function deleted(Wallet $wallet): void
    {
        //
    }

    /**
     * Handle the Wallet "restored" event.
     */
    public function restored(Wallet $wallet): void
    {
        //
    }

    /**
     * Handle the Wallet "force deleted" event.
     */
    public function forceDeleted(Wallet $wallet): void
    {
        //
    }
}
