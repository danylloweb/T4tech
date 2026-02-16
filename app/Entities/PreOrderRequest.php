<?php

namespace App\Entities;

use MongoDB\Laravel\Eloquent\Model;

class PreOrderRequest extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'pre_order_requests';

    protected $fillable = [
        'external_order_id',
        'order',
        'processed'
    ];

    protected $casts = [
        'precessed'  => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
