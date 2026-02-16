<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class ScheduleStatusLog.
 *
 * @package namespace App\Entities;
 */
class ScheduleStatusLog extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'schedule_id',
        'status',
        'author',
        'log'
    ];

    protected array $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static array $rules = [
        'schedule_id' => 'required|integer',
        'status'      => 'required|integer',
        'author'      => 'string',
        'log'         => 'string'
    ];


}
