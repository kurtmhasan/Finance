<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stocks extends Model
{
    use HasFactory;
    protected $table = 'stocks';

    public function master()
    {
        // Foreign key ismini açıkça belirtiyoruz
        return $this->belongsTo(StocksMaster::class, 'stocks_master_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function trades(){
        return $this->hasMany(StocksTrade::class, 'stock_id');
    }
}
