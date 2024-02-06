<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nex_wallet extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'wallet_transaction_type',
        'wallet_amount',
        'wallet_remarks',
        'wallet_transaction_id'

    ];
}
