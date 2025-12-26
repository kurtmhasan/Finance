<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WalletController extends Controller
{
    /**
     * Cüzdan ana sayfasını gösterir.
     */
    public function index(): View
    {
        return view('investment-page');
    }

    /**
     * Para transfer sayfasını gösterir.
     */
    public function transfer(): View
    {
        return view('moneyTransfer-page');
    }

    public function showDashboardData(){
        $user = Auth::user();
        $wallet_number = $user->wallet->wallet_number;
        $balance = $user->wallet->balance;
        return response()->json([
            'wallet_number' => $wallet_number,
            'balance' => $balance
        ]);
    }
}
