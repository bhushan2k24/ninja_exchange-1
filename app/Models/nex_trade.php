<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nex_trade extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'created_by',
        'script_expires_id',
        'trade_bidrate',
        'trade_askrate',
        'trade_change',
        'trade_netchange',
        'trade_high',
        'trade_low',
        'trade_ltp',
        'trade_open',
        'trade_close',
        'trade_quantity',
        'trade_lot',
        'trade_price',
        'trade_type',
        'trade_order_type',
        'trade_status',
        'trade_reference_id',
        'user_ip'

    ];
}
