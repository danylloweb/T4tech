<?php

namespace App\Entities;

use MongoDB\Laravel\Eloquent\Model;

/**
 * Class LocationRange.
 *
 * @package namespace App\Entities;
 */
class Lead extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'leads';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'notification_sms',
        'notification_email',
        'notification_whatsapp',
        'quotation',
    ];

    protected array $dates = [
        'created_at',
        'updated_at'
    ];
}
