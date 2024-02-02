<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nex_watchlist extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'marke_id',
        'script_id',
        'watchlist_script_expiry_date',
        'watchlist_instrument_type',
        'watchlist_script_strike_price',
        'watchlist_script_extension',
        'watchlist_marke_name',
        'watchlist_script_name',
    ];
}
