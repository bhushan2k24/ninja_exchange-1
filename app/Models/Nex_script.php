<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nex_script extends Model
{
    use HasFactory;
    protected $fillable = [
        'market_name','script_name', 'script_quantity','market_id','is_ban','script_full_name'
    ];
}
