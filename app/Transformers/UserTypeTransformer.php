<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\UserType;

/**
 * Class UserTypeTransformer.
 *
 * @package namespace App\Transformers;
 */
class UserTypeTransformer extends TransformerAbstract
{
    /**
     * Transform the UserType entity.
     *
     * @param \App\Entities\UserType $model
     *
     * @return array
     */
    public function transform(UserType $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
