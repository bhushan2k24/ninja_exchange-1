<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nex_time_setting extends Model
{
    use HasFactory;
    protected $fillable = [
        'market_id','script_id','start_time','end_time'
    ];
}
