<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class OrderItem.
 *
 * @package namespace App\Entities;
 */
class OrderItem extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'external_order_id',
        'ref_id',
        'ref_parent_id',
        'type',
        'price',
        'quantity',
        'ref_image_url',
        'ref_parent_image_url',
        'ref_description',
        'ref_parent_description',
        'customer_id',
        'status',
        'metadata',
        'pin_code',
        'review_already_requested'
    ];
    /**
     * @var array|string[] $dates
     */
    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    /**
     * @var array|string[] $rules
     */
    public static array $rules = [
        'order_id'               => 'required|integer',
        'external_order_id'      => 'required|string',
        'ref_id'                 => 'required|string',
        'ref_parent_id'          => 'required|string',
        'type'                   => 'required|integer',
        'price'                  => 'required|numeric',
        'quantity'               => 'required|integer',
        'ref_image_url'          => 'required|string',
        'ref_parent_image_url'   => 'required|string',
        'ref_description'        => 'required|string',
        'ref_parent_description' => 'required|string',
        'customer_id'            => 'required|integer',
        'status'                 => 'required|integer',
        'pin_code'               => 'numeric'
    ];

    /**
     * @return HasOne
     */
    public function schedule(): HasOne
    {
        return $this->hasOne(Schedule::class, 'order_item_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function serviceConfiguration(): HasOne
    {
        return $this->hasOne(ServiceConfiguration::class, 'ref_id', 'ref_id');
    }

    /**
     * @return string
     */
    public function getCustomerOrderByAttribute(): string
    {
        return Order::query()->where('id', $this->attributes['order_id'])->first()->customer;
    }

    /**
     * @return string
     */
    public function getOrderHashByAttribute(): string
    {
        return Order::query()->select(['order_hash'])->where('id', $this->attributes['order_id'])->first()->order_hash;
    }

    public function getProviderLambdaAttributes(): array
    {
        $order = Order::query()->select(['order_hash','zip_code'])->where('id', $this->attributes['order_id'])->first();
        return ['order_hash' => $order->order_hash, 'zip_code' => $order->zip_code];
    }
}
