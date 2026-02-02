<?php

namespace App\Http\Controllers;

use App\Models\Stocks;
use App\Models\StocksMaster;
use App\Models\StocksTrade;
use App\Models\InvestmentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestController extends Controller
{
    public function index()
    {
        return view('invest-page');
    }
    public function getStockName(Request $request)
    {

        $symbol = strtoupper($request->input('symbol'));
        $master = StocksMaster::where('symbol', $symbol)->first();
        if (!$master) {
            return response()->json(['error' => 'Hisse bulunamadı'], 404);
        }

        return response()->json([
            'name' => $master->name
        ]);
    }

    public function buyStock(Request $request)
    {
        $userId = Auth::user()->id;
        $symbol = strtoupper($request->input('symbol'));
        $quantity = (int) $request->input('quantity');

        $master = StocksMaster::where('symbol', $symbol)->firstOrFail();
        $stock = Stocks::where('stocks_master_id', $master->id)
            ->where('user_id', $userId)
            ->first();

        if (!$stock) {
            // yeni stok oluştur
            $stock = new Stocks();
            $stock->user_id = $userId;
            $stock->investment_type_id = $master->investment_type_id;
            $stock->stocks_master_id = $master->id;
            $stock->quantity = $quantity;
            $stock->save();
        } else {
            // mevcut stoku artır
            $stock->quantity += $quantity;
            $stock->save();
        }

        // trade kaydı
        $trade = new StocksTrade();
        $trade->user_id = $userId;
        $trade->stock_id = $stock->id;
        $trade->type = 'buy';
        $trade->price = 50.00; // örnek
        $trade->quantity = $quantity;
        $trade->save();
    }

}
