<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Schedule.
 *
 * @package namespace App\Entities;
 */
class Schedule extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'order_id',
        'order_item_id',
        'provider_id',
        'professional_id',
        'ref_id',
        'ref_parent_id',
        'channel',
        'when_date',
        'when_time_start',
        'when_time_end',
        'status'
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'when_date',
    ];

    public static array $rules = [
        'customer_id'     => 'required|integer',
        'order_id'        => 'required|integer',
        'order_item_id'   => 'required|integer',
        'provider_id'     => 'required|integer',
        'professional_id' => 'integer',
        'ref_id'          => 'required|string',
        'ref_parent_id'   => 'required|string',
        'channel'         => 'required|integer',
        'when_date'       => 'date',
        'when_time_start' => 'time',
        'when_time_end'   => 'time',
        'status'          => 'required|integer',
    ];

    /**
     * @return BelongsTo
     */
    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class)->withTrashed();
    }

    /**
     * @return HasMany
     */
    public function scheduleStatusLog(): HasMany
    {
        return $this->hasMany(ScheduleStatusLog::class);
    }

    /**
     * @return HasOne
     */
    public function professionalRating(): HasOne
    {
        return $this->hasOne(ProfessionalRating::class);
    }
    /**
     * @return HasOne
     */
    public function scheduleNetPromoterScore(): HasOne
    {
        return $this->hasOne(ScheduleNetPromoterScore::class);
    }

    public function getExternalOrderIdAttribute(): string
    {
        return Order::query()->where('id', $this->attributes['order_id'])->first()->external_order_id;
    }

    /**
     * @return HasOne
     */
    public function serviceConfiguration(): HasOne
    {
        return $this->hasOne(ServiceConfiguration::class, 'ref_id', 'ref_id');
    }

    public function getZipCodeAndDeliveryDateByAttribute(): array
    {
        $order = Order::query()->where('id', $this->attributes['order_id'])->first();
        return ["zip_code" =>  $order->zip_code, "delivery_date" => $order->delivery_date];
    }
}
