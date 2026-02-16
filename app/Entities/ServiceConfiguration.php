<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class ServiceConfiguration.
 *
 * @package namespace App\Entities;
 */
class ServiceConfiguration extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ref_id_provider',
        'ref_id',
        'provider_id',
        'name',
        'description',
        'terms_and_conditions',
        'type',
        'slug',
        'instructions'
    ];
    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static array $rules = [
        'ref_id_provider'      => 'required|string|max:50',
        'ref_id'               => 'required|string|max:50',
        'provider_id'          => 'required|string|max:50',
        'name'                 => 'required|string',
        'description'          => 'string',
        'type'                 => 'integer',
        'slug'                 => 'string|max:50',
        'terms_and_conditions' => 'json',
        'instructions'         => 'json'
    ];

}
