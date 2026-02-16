<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class ServiceAvailabilityLocation.
 *
 * @package namespace App\Entities;
 */
class ServiceAvailabilityLocation extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location_range_id',
        'ref_id'
    ];
    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static array $rules = [
        'location_range_id' => 'required|integer',
        'ref_id'               => 'required|string'
    ];

    public function locationRange(): BelongsTo
    {
        return $this->belongsTo(LocationRange::class);
    }
}
