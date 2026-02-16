<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Professional.
 *
 * @package namespace App\Entities;
 */
class Professional extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
        'provider_id',
        'document',
        'avatar_url',
        'stars',
        'services_performed',
        'detail'
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static array $rules = [
        'name'        => 'required|string',
        'status'      => 'required|string',
        'provider_id' => 'required|integer',
        'document'    => 'required|string',
        'avatar_url'  => 'required|string',
        'stars'       => 'required|integer|min:1|max:5',
        'services_performed' => 'required|integer|min:0',
        'detail'      => 'nullable|array'
    ];

}
