<?php
namespace App\Services;
use App\Models\Nex_trade;

class TradeOrder 
{
    public function createOreder(array $tradeData)
    {
        $user = Nex_trade::create($tradeData);
    }
}