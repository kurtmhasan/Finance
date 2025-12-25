<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Exception;
class TransferService
{
    /**
     * Para transfer işlemini gerçekleştirir.
     * * @param User $sender   Gönderen kullanıcı nesnesi
     * @param User $receiver Alıcı kullanıcı nesnesi
     * @param float $amount  Gönderilecek miktar
     * @return bool
     * @throws Exception
     */
    public function execute(User $sender, Wallet $receiverWallet, float $amount): bool
    {
        // DB::transaction ya hep ya hiç mantığı sağlıyor bir işlem fail olursa rollback atıyor
           return DB::transaction(function () use ($sender, $receiverWallet, $amount) {

            // yarış durumununu (race condition) engeller lockForUpdate() ile o satırı kitler
            $senderWallet   = $sender->wallet()->lockForUpdate()->first();
            $receiverWallet = Wallet::where('id', $receiverWallet->id)->lockForUpdate()->first();

            if ($senderWallet->wallet_number === $receiverWallet->wallet_number) {
                throw new Exception("Kendi cüzdanınıza transfer yapamazsınız.");
            }
            // Bakiye düşme
            $senderWallet->decrementBalance($amount);
            // Bakiye ekleme
            $receiverWallet->increment('balance', $amount);
            return true;
        });
    }

}
