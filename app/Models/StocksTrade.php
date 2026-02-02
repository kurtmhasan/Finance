<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StocksTrade extends Model
{
    use HasFactory;
    protected $table = 'stock_trades';

    public function stock(){
        return $this->belongsTo(Stocks::class, 'stock_id');
    }
}
