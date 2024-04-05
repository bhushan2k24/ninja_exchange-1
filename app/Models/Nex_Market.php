<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nex_Market extends Model
{
    use HasFactory;
    protected $table = 'nex_markets';
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'market_user_required_fields' => 'array',
        'user_required_fields' => 'array',
    ];
}
