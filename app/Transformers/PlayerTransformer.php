<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Player;

/**
 * Class PlayerTransformer.
 *
 * @package namespace App\Transformers;
 */
class PlayerTransformer extends TransformerAbstract
{

    /**
     * Transform the Player entity.
     *
     * @param Player $model
     *
     * @return array
     */
    public function transform(Player $model)
    {
        return [
            'id'            => (int) $model->id,
            'first_name'    => $model->first_name,
            'last_name'     => $model->last_name,
            'position'      => $model->position,
            'height'        => $model->height,
            'weight'        => $model->weight,
            'jersey_number' => $model->jersey_number,
            'college'       => $model->college,
            'country'       => $model->country,
            'draft_year'    => $model->draft_year,
            'draft_round'   => $model->draft_round,
            'draft_number'  => $model->draft_number,
            'team_id'       => $model->team_id,
            'created_at'    => $model->created_at->toDateTimeString(),
            'updated_at'    => $model->updated_at->toDateTimeString(),
        ];
    }

}
