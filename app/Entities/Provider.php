<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Provider.
 *
 * @package namespace App\Entities;
 */
class Provider extends Model implements Transformable
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
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static array $rules = [
        'name'   => 'required|string',
        'status' => 'required|string',
    ];
}
