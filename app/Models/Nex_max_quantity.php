<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nex_max_quantity extends Model
{
    use HasFactory;
    protected $fillable = [
        'level_id','level_name','market_id','market_name','script_id','script_name','max_quantity_position','max_quantity_min_order','max_quantity_max_order'
    ];
}
