<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Game;

/**
 * Class GameTransformer.
 *
 * @package namespace App\Transformers;
 */
class GameTransformer extends TransformerAbstract
{


    /**
     * Transform the Game entity.
     *
     * @param \App\Entities\Game $model
     *
     * @return array
     */
    public function transform(Game $model): array
    {
        return [
            'id'                         => (int) $model->id,
            'date'                       => $model->date,
            'season'                     => $model->season,
            'status'                     => $model->status,
            'period'                     => $model->period,
            'time'                       => $model->time,
            'postseason'                 => $model->postseason,
            'postponed'                  => $model->postponed,
            'home_team_score'            => $model->home_team_score,
            'visitor_team_score'         => $model->visitor_team_score,
            'datetime'                   => $model->datetime,
            'home_q1'                    => $model->home_q1,
            'home_q2'                    => $model->home_q2,
            'home_q3'                    => $model->home_q3,
            'home_q4'                    => $model->home_q4,
            'home_ot1'                   => $model->home_ot1,
            'home_ot2'                   => $model->home_ot2,
            'home_ot3'                   => $model->home_ot3,
            'home_timeouts_remaining'    => $model->home_timeouts_remaining,
            'home_in_bonus'              => $model->home_in_bonus,
            'visitor_q1'                 => $model->visitor_q1,
            'visitor_q2'                 => $model->visitor_q2,
            'visitor_q3'                 => $model->visitor_q3,
            'visitor_q4'                 => $model->visitor_q4,
            'visitor_ot1'                => $model->visitor_ot1,
            'visitor_ot2'                => $model->visitor_ot2,
            'visitor_ot3'                => $model->visitor_ot3,
            'visitor_timeouts_remaining' => $model->visitor_timeouts_remaining,
            'visitor_in_bonus'           => $model->visitor_in_bonus,
            'ist_stage'                  => $model->ist_stage,
            'home_team_id'               => $model->home_team_id,
            'visitor_team_id'            => $model->visitor_team_id,
            'created_at'                 => $model->created_at->toDateTimeString(),
            'updated_at'                 => $model->updated_at->toDateTimeString()
        ];
    }


}
