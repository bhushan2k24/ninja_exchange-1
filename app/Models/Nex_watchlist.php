<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nex_watchlist extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'market_id',
        'script_id',
        'script_expires_id',
        'watchlist_script_expiry_date',
        'watchlist_trading_symbol',
        'watchlist_instrument_type',
        'watchlist_script_strike_price',
        'watchlist_script_extension',
        'watchlist_market_name',
        'watchlist_script_name',
    ];
}
