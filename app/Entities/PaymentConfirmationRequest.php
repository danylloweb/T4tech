<?php

namespace App\Entities;

use MongoDB\Laravel\Eloquent\Model;

class PaymentConfirmationRequest extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'payment_confirmation_requests';

    protected $fillable = [
        'external_order_id',
        'processed'
    ];

    protected $casts = [
        'precessed'  => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
