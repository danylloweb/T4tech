<?php

namespace App\Entities;

use MongoDB\Laravel\Eloquent\Model;

class PricingService extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'pricing_services';

    protected $fillable = [
        'ref_id',
        'ref_parent_id',
        'provider_id',
        'amount',
        'status'
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

}
