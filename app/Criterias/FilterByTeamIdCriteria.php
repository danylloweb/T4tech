<?php

namespace App\Criterias;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class FilterByTeamIdCriteria
 * @package namespace App\Criteria;
 */
class FilterByTeamIdCriteria extends AppCriteria implements CriteriaInterface
{

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $team_id = $this->request->query->get('team_id');
        if (is_numeric($team_id)) {
            $model = $model->where('team_id', $team_id);
        }
        return $model;
    }
}
