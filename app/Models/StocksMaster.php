<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StocksMaster extends Model
{
    protected $table = 'stocks_master';

    protected $fillable = ['symbol', 'name', 'investment_type_id'];
    public function stocks(){
        return $this->hasMany(Stocks::class, 'stocks_master_id');
    }
    public function investmentType(){
        return $this->belongsTo(InvestmentType::class, 'investment_type_id');
    }
}
