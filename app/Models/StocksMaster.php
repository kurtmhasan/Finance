<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StocksMaster extends Model
{
    protected $table = 'stocks_master';

    protected $fillable = ['symbol', 'name'];
}
