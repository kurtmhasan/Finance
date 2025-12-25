<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Transaction;
class TransferController extends Controller
{
    public function sendMoney(Request $request){
        $user = Auth::user();
        if($request->amount > 0){

        }
        $receiver_wallet_number = $request->wallet_number;
        $amount = $request->amount;
        $balance = Wallet::where('wallet_number', $receiver_wallet_number)->first();
        $balance->balance = $balance->balance + $amount;
        $user->wallet->balance = $user->wallet->balance - $amount;
        $balance->save();
        $user->wallet->save();


    }
}
