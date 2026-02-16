<?php

namespace App\Criterias;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class FilterByStatusIdCriteria
 * @package namespace App\Criteria;
 */
class FilterByStatusIdCriteria extends AppCriteria implements CriteriaInterface
{

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository): mixed
    {
        $status = $this->request->query->get('status');
        if (is_numeric($status)) {
            $model = $model->where('status', $status);
        }
        return $model;
    }
}
