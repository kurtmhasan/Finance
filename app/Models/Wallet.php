<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $fillable = ['balance', 'wallet_number'];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function decrementBalance(float $amount): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException("eksi bakiye gönderemezsin.");
        }

        if ($this->balance < $amount) {
            throw new \RuntimeException("bakiyeniz bu işlem içiin yetersiz.");
        }

        $this->decrement('balance', $amount);
    }

}
