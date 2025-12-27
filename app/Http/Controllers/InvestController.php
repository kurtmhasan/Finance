<?php

namespace App\Http\Controllers;

use App\Models\Stocks;
use App\Models\StocksMaster;
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

    public function buyStock(Request $request){
        $userId = auth()->id() ?: 1;
        $symbol = strtoupper($request->input('symbol'));
        $master = StocksMaster::where('symbol', $symbol)->first();

        if($master->id != Stocks::where('stocks_master_id', $master->id)->exists()){
            $newStock = new Stocks();
            $newStock->user_id = $userId;
            $newStock->investment_type_id = 1;
            $newStock->stocks_master_id = $master->id;
            $newStock->save();
        }
        // stock_trade sayfasını doldurcan



    }
}
