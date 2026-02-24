<?php

namespace App\Http\Controllers;

use App\Models\Stocks;
use App\Models\StocksMaster;
use App\Models\StocksTrade;
use App\Models\InvestmentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
class InvestController extends Controller
{
    public function index()
    {
        return view('invest-page');
    }
    public function getStockName(Request $request)
    {
        $url = "http://127.0.0.1:8001/price/{$request->symbol}";
        $master = Http::timeout(5)->get($url);
        if ($master->successful()) {
            $data = $master->json();
            return response()->json($data);
        }
    }

    public function buyStock(Request $request)
    {
        $userId = Auth::user()->id;
        $symbol = strtoupper($request->input('symbol'));
        $quantity = (int) $request->input('quantity');

        $scraperResponse = Http::get("http://127.0.0.1:8001/price/{$request->symbol}");
        $actualPrice = $scraperResponse->json()['price'];

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
        $trade->price = $actualPrice; // örnek
        $trade->quantity = $quantity;
        $trade->save();
    }

}
