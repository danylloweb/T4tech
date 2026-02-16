<?php

namespace App\Entities;

use MongoDB\Laravel\Eloquent\Model;

/**
 * Class Quotation.
 *
 * @package namespace App\Entities;
 */
class Quotation extends Model
{
    protected $connection = 'mongodb';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'serviceSku',
        'serviceType',
        'availability',
        'quotations',
    ];

    protected array $dates = [
        'created_at',
        'updated_at'
    ];
}
