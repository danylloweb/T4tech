<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class NetPromoterScore.
 *
 * @package namespace App\Entities;
 */
class NetPromoterScore extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'external_order_id',
        'order_id',
        'schedule_id',
        'rating',
        'comment',
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
        'order_id'    => 'required|integer',
        'schedule_id' => 'required|integer',
        'rating'      => 'required|integer',
        'comment'     => 'nullable|string',
    ];

}
