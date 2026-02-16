<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Pricing.
 *
 * @package namespace App\Entities;
 */
class Pricing extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
    /**
     * @var array|string[] $rules
     */
    public static array $rules =
    [
        'ref_id'        => 'required|string',
        'ref_parent_id' => 'required|string',
        'provider_id'   => 'required|integer',
        'amount'        => 'required|numeric',
        'status'        => 'required|integer'
    ];

}
