<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nex_Level extends Model
{
    use HasFactory;
    protected $table = 'nex_levels';
    protected $fillable = [
        'level_name','level_status'
    ];
}
