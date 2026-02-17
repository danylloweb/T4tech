<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Game.
 *
 * @package namespace App\Entities;
 */
class Game extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'season',
        'status',
        'period',
        'time',
        'postseason',
        'postponed',
        'home_team_score',
        'visitor_team_score',
        'datetime',
        'home_q1',
        'home_q2',
        'home_q3',
        'home_q4',
        'home_ot1',
        'home_ot2',
        'home_ot3',
        'home_timeouts_remaining',
        'home_in_bonus',
        'visitor_q1',
        'visitor_q2',
        'visitor_q3',
        'visitor_q4',
        'visitor_ot1',
        'visitor_ot2',
        'visitor_ot3',
        'visitor_timeouts_remaining',
        'visitor_in_bonus',
        'ist_stage',
        'home_team_id',
        'visitor_team_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    /**
     * Get the home team.
     */
    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    /**
     * Get the visitor team.
     */
    public function visitorTeam()
    {
        return $this->belongsTo(Team::class, 'visitor_team_id');
    }
}
