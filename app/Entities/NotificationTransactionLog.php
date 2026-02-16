<?php

namespace App\Entities;

use MongoDB\Laravel\Eloquent\Model;

/**
 * Class NotificationTransactionLog.
 *
 * @package namespace App\Entities;
 */
class NotificationTransactionLog extends Model
{
    protected $connection = 'mongodb';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'external_order_id',
        'order_item_id',
        'event_name',
        'notification_payload',
        'notification_response',
    ];

    protected array $dates = [
        'created_at',
        'updated_at'
    ];
}
