<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class ProfessionalScores.
 *
 * @package namespace App\Entities;
 */
class ProfessionalScores extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'professional_id',
        'provider_id',
        'score'
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
        'professional_id' => 'required|integer',
        'provider_id'     => 'required|integer',
        'score'           => 'nullable|json'
    ];
}
