<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\ServiceConfiguration;

/**
 * Class ServiceConfigurationTransformer.
 *
 * @package namespace App\Transformers;
 */
class ServiceConfigurationTransformer extends TransformerAbstract
{
    /**
     * Transform the ServiceConfiguration entity.
     *
     * @param ServiceConfiguration $model
     *
     * @return array
     */
    public function transform(ServiceConfiguration $model): array
    {
        return [
            'id'                   => (int) $model->id,
            'ref_id'               => $model->ref_id,
            'ref_id_provider'      => $model->ref_id_provider,
            'name'                 => $model->name,
            'description'          => $model->description,
            'slug'                 => $model->slug,
            'terms_and_conditions' => $model->terms_and_conditions ? json_decode($model->terms_and_conditions) : null,
            'instructions'         => $model->instructions ? json_decode($model->instructions) : null,
            'created_at'           => $model->created_at->toDateTimeString(),
            'updated_at'           => $model->updated_at->toDateTimeString(),
        ];
    }
}
