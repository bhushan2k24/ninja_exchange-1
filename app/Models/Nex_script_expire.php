<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nex_script_expire extends Model
{
    use HasFactory;
    protected $table = 'nex_script_expires';
    protected $fillable = [
        'market_name','market_id','script_id','script_name', 'expiry_date','script_trading_symbol','script_instrument_type','script_extension','script_strike_price'
    ];
}
