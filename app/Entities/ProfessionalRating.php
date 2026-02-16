<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class ProfessionalRating.
 *
 * @package namespace App\Entities;
 */
class ProfessionalRating extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'order_id',
        'external_order_id',
        'order_item_id',
        'schedule_id',
        'professional_id',
        'provider_id',
        'stars',
        'comment',
        'testimony',
        'arrived_outside_scheduled_window',
        'rude_professional',
        'poor_service',
        'left_environment_dirty',
        'other_bad',
        'arrived_on_time',
        'polite_professional',
        'excellent_service',
        'clean_service',
        'other_good',
        'visibility'
    ];
    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    /**
     * @var array|string[] $rules
     */
    public static array $rules = [
        'customer_id'     => 'required|integer',
        'order_id'        => 'required|integer',
        'order_item_id'   => 'required|integer',
        'schedule_id'     => 'required|integer',
        'professional_id' => 'required|integer',
        'provider_id'     => 'required|integer',
        'stars'           => 'required|integer',
        'comment'         => 'nullable|string',
        'testimony'       => 'nullable|string',
        'arrived_outside_scheduled_window' => 'boolean',
        'rude_professional'                => 'boolean',
        'poor_service'                     => 'boolean',
        'left_environment_dirty'           => 'boolean',
        'other_bad'                        => 'boolean',
        'arrived_on_time'      => 'boolean',
        'polite_professional'  => 'boolean',
        'excellent_service'    => 'boolean',
        'clean_service'        => 'boolean',
        'other_good'           => 'boolean',
        'visibility'           => 'string',
    ];


    public function getExternalOrderByAttribute(): string
    {
        return Order::query()->where('id', $this->attributes['order_id'])->first()->external_order_id ?? '';
    }

    /**
     * @return BelongsTo
     */
    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class, 'professional_id');
    }

    /**
     * @return string
     */
    public function getCustomerOrderByAttribute(): string
    {
        return Order::query()->where('id', $this->attributes['order_id'])->first()->customer;
    }
    /**
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
     */
    public static function getRatingNps($schedule_id): mixed
    {
        return NetPromoterScore::query()->where('schedule_id', $schedule_id)->first()->rating??0;
    }

}
