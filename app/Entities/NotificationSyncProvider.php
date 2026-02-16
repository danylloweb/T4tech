<?php

namespace App\Entities;

use MongoDB\Laravel\Eloquent\Model;

/**
 * Class NotificationSyncProvider.
 *
 * @package namespace App\Entities;
 */
class NotificationSyncProvider extends Model
{
    protected $connection = 'mongodb';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'external_order_id',
        'schedule_id',
        'order_item_id',
        'notification_payload',
        'notification_response',
    ];

    protected array $dates = [
        'created_at',
        'updated_at'
    ];
}
