<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nex_live_tv extends Model
{
    use HasFactory;
    protected $fillable = [
        'language','video_link'
    ];
}
