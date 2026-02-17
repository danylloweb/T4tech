<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Player.
 *
 * @package namespace App\Entities;
 */
class Player extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'position',
        'height',
        'weight',
        'jersey_number',
        'college',
        'country',
        'draft_year',
        'draft_round',
        'draft_number',
        'team_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get the team that owns the player.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
