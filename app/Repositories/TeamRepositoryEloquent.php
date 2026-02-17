<?php

namespace App\Repositories;

use App\Presenters\TeamPresenter;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TeamRepository;
use App\Entities\Team;
use App\Validators\TeamValidator;

/**
 * Class TeamRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TeamRepositoryEloquent extends BaseRepository implements TeamRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Team::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return TeamValidator::class;
    }

    public function presenter()
    {
        return TeamPresenter::class;
    }

}
