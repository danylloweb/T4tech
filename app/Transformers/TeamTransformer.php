<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Team;

/**
 * Class TeamTransformer.
 *
 * @package namespace App\Transformers;
 */
class TeamTransformer extends TransformerAbstract
{
    /**
     * Transform the Team entity.
     *
     * @param \App\Entities\Team $model
     *
     * @return array
     */
    public function transform(Team $model): array
    {
        return [
            'id'           => (int) $model->id,
            'conference'   => $model->conference,
            'division'     => $model->division,
            'city'         => $model->city,
            'name'         => $model->name,
            'full_name'    => $model->full_name,
            'abbreviation' => $model->abbreviation,
            'created_at'   => $model->created_at->toDateTimeString(),
            'updated_at'   => $model->updated_at->toDateTimeString(),
        ];
    }
}
