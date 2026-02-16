<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Order.
 *
 * @package namespace App\Entities;
 */
class Order extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_hash',
        'session_id',
        'external_order_id',
        'main_external_order_id',
        'zip_code',
        'customer_id',
        'customer',
        'provider_id',
        'channel',
        'total',
        'status',
        'estimated_delivery_date',
        'delivery_date'
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public static array $rules = [
        'zip_code'               => 'required|string',
        'order_hash'             => 'required|string',
        'session_id'             => 'required|string',
        'customer_id'            => 'required|string',
        'customer'               => 'required|string',
        'provider_id'            => 'required|integer',
        'channel'                => 'required|string',
        'total'                  => 'required|numeric',
        'status'                 => 'required|string'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
