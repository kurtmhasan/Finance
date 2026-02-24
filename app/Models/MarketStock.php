<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketStock extends Model
{
    protected $table = 'market_stocks';

    protected $fillable = [
        'symbol',
        'name',
        'current_price',
        'last_fetched_at'
    ];
}
