<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletController extends Controller
{
    /**
     * Cüzdan ana sayfasını gösterir.
     */
    public function index(): View
    {
        return view('wallet-page');
    }

    /**
     * Para transfer sayfasını gösterir.
     */
    public function transfer(): View
    {
        return view('moneyTransfer-page');
    }
}
