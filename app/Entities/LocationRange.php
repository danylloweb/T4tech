<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class LocationRange.
 *
 * @package namespace App\Entities;
 */
class LocationRange extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'min',
        'max',
        'location'
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
    ];

    public static array $rules = [
        'min'      => 'required|numeric',
        'max'      => 'required|numeric',
        'location' => 'required|string'
    ];

    public function serviceAvailabilityLocations(): HasMany
    {
        return $this->hasMany(ServiceAvailabilityLocation::class);
    }

    public function isActive(): bool
    {
        return $this->serviceAvailabilityLocations()->exists();
    }
}
