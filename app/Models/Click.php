<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    protected $fillable = [
        'click_id',
        'offer_id',
        'source',
        'clicked_at',
        'signature',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];
}
